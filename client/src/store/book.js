export const state = () => ({
  books: [],
  book: null,
  currentPage: 0,
  lastPage: null
})

export const mutations = {
  setBooks (state, books) {
    state.books = books
  },
  addBooks (state, books) {
    state.books.push(...books)
  },
  setPage (state, { currentPage, lastPage }) {
    state.currentPage = currentPage
    state.lastPage = lastPage
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
  async fetchBooks ({ state, commit }, { group, s }) {
    try {
      const { status, data: { data: books, current_page, last_page } } =
        await this.$axios.get(`/books/${group}`, {
          params: { page: state.currentPage + 1, s }
        })
      commit('addBooks', books)
      commit('setPage', { currentPage: current_page, lastPage: last_page })
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
