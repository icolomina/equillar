// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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