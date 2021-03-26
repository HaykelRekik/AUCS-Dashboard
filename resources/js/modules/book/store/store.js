import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        books: [],
        booksMeta: [],
        booksLoading: true,
    },
    getters: {
        books: state => state.books,
        booksMeta: state => state.booksMeta,
        booksLoading: state => state.booksLoading,
    },
    mutations: {
        [types.BOOK_OBTAIN](state, books) {
            state.books = books
        },
        [types.BOOK_CLEAR](state) {
            state.books = []
        },
        [types.BOOK_SET_LOADING](state, loading) {
            state.booksLoading = loading
        },
        [types.BOOK_META](state, meta) {
            state.booksMeta = meta
        },
    },
    actions
}
