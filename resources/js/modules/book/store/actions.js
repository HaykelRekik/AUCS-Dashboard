import * as types from './types'
import bookApi from '../api'

export const actions = {
    async [types.BOOK_FETCH]({commit}, data = null) {
        commit(types.BOOK_SET_LOADING, true)
        const response = await bookApi.all(data)
        commit(types.BOOK_OBTAIN, response.data.data)
        commit(types.BOOK_META, response.data.meta)
        commit(types.BOOK_SET_LOADING, false)
    },
}
