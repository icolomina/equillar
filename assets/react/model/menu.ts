/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

export interface TableRowMenuActon<T> {
    name: string
    callable: (element: T) => void,
    role?: string[]
}

export interface TableRowMenu<T extends object> {
    element: T,
    actions: TableRowMenuActon<T>[]
}

export interface IconAction {
    id: string,
    icon: any,
    text: string,
    onClick?: () => void
}