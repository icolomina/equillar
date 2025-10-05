## Backend structure
This section explains how the backend code is organized. It examines the folder organization and what types of services/classes are hosted in each folder.

### Application Folder
This namespace contains the application's core services. Each application entity has its own folder, and each folder may include the following subfolders:

#### Service  
Holds application services that interact with the database and/or the blockchain (smart contracts). Services that access the database reside in the Service folder root; services that call the blockchain live in the Blockchain subfolder.

Examples of database services:

- **CreateContractService**: Responsible for creating a new contract.
- **ApproveContractService**: Responsible for approving an existing contract (changes status to "approved").

Examples of blockchain services:

- **ContractActivatonService**: Responsible for installing a contract on the blockchain and returning its contract identifier.
- **CreateContractBalanceMovementService**: Responsible for executing a movement between two balance segments of a contract.

When adding a new application service, it must be named with a short description of its responsibility and sufixed with the word "Service" as shown above.

#### Transformer
Contains services responsible for converting input DTOs into entities ready for persistence and converting entities into output DTOs for the presentation layer. It also holds logic to mutate (change its properties) existing entities. Examples:

- **ContractEntityTransformer**: Responsible for converting contract input DTOs (for example, a form payload for creating a contract) into Contract entities, and converting Contract entities into output DTOs used by the presentation layer. It also implements mutation logic, e.g., changing a contract's status from REVIEWING to APPROVED.
  
- **ContractTransactionEntityTransformer**: Performs the same responsibilities as ContractEntityTransformer but for ContractTransaction entities.

When adding a new transformer, name it using the entity name followed by EntityTransformer (for example, ContractEntityTransformer).

#### Mapper
Contains services that map the results of smart contract function calls to entity properties that reflect on-chain operations. These mappers take the raw on-chain response and update the corresponding entity with the received data. Example:

- **UserInvestmentTrxResultMapper**: This service gets the result of the contract function call that manages a user project investment and updates the corresponding "UserInvestment" entity with the on-chain received data. 

> This mappers seems to be simple "Transformers" but as they deal with on-chain data, they have been located in another namespace separated from "Transformer".

When adding a new mapper, name it using the related blockchain operation followed by Mapper (for example, GetContractBalanceMapper).

### Blockchain Folder

This namespace contains all the services that provide the logic to deal with the blockchain and the smart-contracts. The only subfolder it contains is "Stellar" since at the moment this application only interacts with the Stellar blockchain. Let's enumerate which is inside of the Stellar subfolder: 

#### Account
Contains the service that loads the Stellar account used to interact with the blockchain. 

- **StellarAccountLoader**: Loads the account (using the keys stored in the SystemWallet entity) that the system uses to interact with Stellar.

#### Exception
Holds all PHP exceptions which are thrown when an error occurs while interacting with the blockchain.

#### Soroban
Contains services that facilitate interaction with Soroban (the Stellar smart‑contract platform). It is organized into four subfolders:

- **Events**: Services that read events emitted by Soroban contracts. The primary service here is **GetEventsService**, which reads events emitted by the investment contract. Currently the investment contract emits an event only when the contract balance changes.

- **ScContract**: Services that create and build contract call operations, wrap them in transactions, and send them to the blockchain. It also contains services that retrieve specific events via **GetEventsService**. ScContract is subdivided into:
   
    - **Event**: Services that retrieve specific emitted event data. Currently available: **GetContractBalanceUpdatedEvents**, which reads contract balance update events.
    - **Operation**: Services that build operations and send them to the blockchain. A Builder subfolder contains a builder service for each contract operation. Services in the Operation folder use those builders to construct and send operations. 
        - When adding a new operation, name it using the related blockchain operation followed by Operation (for example, CheckContractPaymentAvailabilityOperation).
        - When adding a new operation builder, name it using the related blockchain operation followed by OperationBuilder (for example, CheckContractPaymentAvailabilityOperationBuilder).

- **Server**:  Provides a single service that returns a SorobanServer instance for other services to use when retrieving events and sending transactions.
- **Transaction**: Provides a single service responsible for sending a transaction to the blockchain. It throws an exception if an error occurs and waits for the transaction result when the send succeeds.

#### Transaction
Contains a single service that retrieves Stellar transaction information given a transaction hash.


### The Command Folder
Contains Symfony console commands used to manage and prepare the application environment (among other uses). Examples:

- **GenerateSystemWalletCommand**: Generates a Stellar keypair, configures it to receive USDC and EURC, and saves the address in the **SystemWallet** entity.
- **ExecutePaymentsCommand**: Finds pending payments and sends them to investors' addresses.

### The Controller folder
Exposes the application's HTTP endpoints. Controllers are intentionally thin: they validate/deserialize input DTOs, delegate use cases to Application services, and format responses (JSON for the SPA, Twig for internal/admin views).

#### Key points for all the endpoints:

- **Route attributes**: endpoints use Symfony attributes and are grouped by resource.
- **Security**: access is enforced via attributes (e.g., IsGranted), voters, and/or middleware.
- **Responses**: primarily JSON for the SPA; Twig for internal/admin pages when required.
- **Validation**: request DTOs declare constraints; validation errors are returned in a consistent format. Symfony attributes MapRequestPayload and MapQueryString are used to map incoming payloads into input DTOs.

### The DataFixtures folder
Contains the services used to populate the database with data for development and tests. It uses the [DoctrineFixturesBundle](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html) to achieve tis goal.

### The Domain folder
Although named "Domain", this folder is not related to DDD's domain concept. It contains both models and services.

- Models must not be Input/Output DTOs nor entities.
- Services must implement logic that does not interact with the database, smart contracts, or other external services.

Examples of models:

- **Domain\Contract\ContractStatus**: Represents the possible states of a contract.
- **Domain\UserContract\UserContractStatus**: Represents the possible states of a user contract.
- **Domain\Crypt\CryptedValue.php**: Represents a value encrypted using PHP Sodium.

Examples of services:

- **\Domain\Crypt\Service\Encryptor**: Encrypts values using PHP Sodium.
- **\Domain\ScContract\Service\ScContractResultBuilder**: Translates a smart-contract call response (without interacting with the contract) into a PHP value.
  

### The Entity folder
Contains the [Doctrine](https://symfony.com/doc/current/doctrine.html) entities that map to the relational database schema.

### The Event folder
Contains a single [event subscriber](https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber) responsible for formatting certain errors (for example, validation errors) into JSON so they can be handled by the frontend.

### The Message folder
Defines message contracts for asynchronous processing via [Symfony Messenger](https://symfony.com/doc/current/messenger.html). Message classes live directly in this folder; their handlers are placed in the Handler subfolder.

### The Persistence folder
Contains persistence services for each Doctrine entity. Each entity has a storage interface that defines required methods and one or more storage implementations for the project's persistence layers. In this project the persistence layer is Doctrine, so every entity has a Doctrine-based storage implementation. Examples:

- **BlockchainNetworkStorageInterface**: Defines the methods that a **BlockchainNetwork** storage must implement.
- **BlockchainNetworkDoctrineStorage**: Implements **BlockchainNetworkStorageInterface** and uses the Doctrine's entity manager to communicate with the database.

- When adding a new Storage Interface, name it using the entity name followed by StorageInterface (for example, BlockchainNetworkStorageInterface).
- When adding a new Doctrine Interface, name it using the entity name followed by DoctrineStorage (for example, BlockchainNetworkDoctrineStorage) and remember to implement the related interface.

### The Presentation folder
Contains DTO classes used by endpoints: Input DTOs for receiving request data and Output DTOs for returning response data. For entities, each one gets a subfolder that contains at least two subfolders:

- **DTO/Input**: Input DTOs that map controller request payloads.
- **DTO/Output**: Output DTOs returned by controller responses.

- When adding a new Input Dto, name it using the entity name or concrete model (maybe a partial result) followed by DtoInput (for example, RegisterUserDtoInput).
- When adding a new Output Dto, name it using the entity name or concrete model (maybe a partial result) followed by DtoOutput (for example, UserContractDtoOutput).

### The Repository folder
Contains concrete Doctrine repository implementations for the entities. Responsibilities:

- Encapsulate complex queries.
- Provide specific lookup methods required by storages
- The Doctrine storages use these repositories through the Doctrine entity manager.

### The Security folder

Contains all services related to application security. This folder is divided into three subfolders:

- **Authenticator**: Contains the Symfony [firewall](https://symfony.com/doc/current/security.html#authenticating-users) used to authenticate users with a JWT token.
- **Authorization**: Contains the Symfony [voters](https://symfony.com/doc/current/security/voters.html) used to protect specific routes.
- **Uri**: Contains a service that validates URL signatures using Symfony’s [UriSigner](https://symfony.com/blog/new-in-symfony-5-1-improved-urisigner).