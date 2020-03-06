export const state = () => ({
  books: [],
  book: null
})

export const mutations = {
  setBooks (state, books) {
    state.books = books
  },
  addBooks (state, books) {
    state.books.push(...books)
  },
  setBook (state, book) {
    state.book = book
  },
  setFavorite(state, { bookId, wiiBeFavorite }) {
    const index = state.books.findIndex(book => book.book_id === bookId)
    const target = state.books.find(book => book.book_id === bookId)
    target.book_is_favorite = wiiBeFavorite
    target.book_favorite_count = wiiBeFavorite ?
      ++target.book_favorite_count :
      --target.book_favorite_count
    state.books.splice(index, 1, target)
  }
}

export const actions = {
  async fetchBooks ({ commit }, { group, page = 1, s }) {
    try {
      const { status, data: { data: books } } =
        await this.$axios.get(`/books/${group}`, { params: { page, s } })
      commit('addBooks', books)
      return status
    } catch (e) {
      return e.response.status
    }
  },
  async saveFavorite ({ commit }, { bookId }) {
    try {
      await this.$axios.post(`/favorite/${bookId}`)
      commit('setFavorite', { bookId, wiiBeFavorite: true })
    } catch (e) {
      console.log(e.response)
    }
  },
  async deleteFavorite ({ commit }, { bookId }) {
    try {
      await this.$axios.delete(`/favorite/${bookId}`)
      commit('setFavorite', { bookId, wiiBeFavorite: false })
    } catch (e) {
      console.log(e.response)
    }
  }
}
