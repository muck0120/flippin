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
    if (index !== -1 && target) {
      target.book_is_favorite = wiiBeFavorite
      target.book_favorite_count = wiiBeFavorite ?
        ++target.book_favorite_count :
        --target.book_favorite_count
      state.books.splice(index, 1, target)
    }
    if (state.book && state.book.book_id === bookId) {
      state.book.book_is_favorite = wiiBeFavorite
      state.book.book_favorite_count = wiiBeFavorite ?
        ++state.book.book_favorite_count :
        --state.book.book_favorite_count
    }
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
  async fetchBook ({ commit }, { bookId, cardId = null }) {
    try {
      const { status, data: { book } } =
        await this.$axios.get(`/books/${bookId}`)
      commit('setBook', book)
      commit('card/setCards', book.book_cards, { root: true })
      if (cardId) {
        const card = book.book_cards.find(card => {
          return card.card_id === parseInt(cardId)
        })
        commit('card/setCard', card, { root: true })
      }
      return status
    } catch (e) {
      return e.response.status
    }
  },
  async storeBook ({ commit }, { title, desc, isPublish }) {
    try {
      const { status, data: { book } } =
        await this.$axios.post('/books/book', {
          book_title: title,
          book_desc: desc,
          book_is_publish: isPublish
        })
      commit('setBook', book)
      return status
    } catch (e) {
      return e.response.status
    }
  },
  async updateBook ({ commit }, { bookId, title, desc, isPublish }) {
    try {
      const { status, data: { book } } =
        await this.$axios.put(`/books/${bookId}`, {
          book_title: title,
          book_desc: desc,
          book_is_publish: isPublish
        })
      commit('setBook', book)
      return status
    } catch (e) {
      return e.response.status
    }
  },
  async deleteBook ({}, { bookId }) {
    try {
      const { status } = await this.$axios.delete(`/books/${bookId}`)
      return status
    } catch (e) {
      return e.response.status
    }
  },
  async storeFavorite ({ commit }, { bookId }) {
    try {
      await this.$axios.post(`/favorite/${bookId}`)
      commit('setFavorite', { bookId, wiiBeFavorite: true })
    } catch (e) {
      console.log(e)
    }
  },
  async deleteFavorite ({ commit }, { bookId }) {
    try {
      await this.$axios.delete(`/favorite/${bookId}`)
      commit('setFavorite', { bookId, wiiBeFavorite: false })
    } catch (e) {
      console.log(e)
    }
  }
}
