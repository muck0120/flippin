export const state = () => ({
  cards: [],
  card: null
})

export const mutations = {
  setCards (state, cards) {
    state.cards = cards
  },
  setCard (state, card) {
    state.card = card
  }
}

export const actions = {
  async updateCardOrder ({ commit }, { bookId, payload, cards }) {
    try {
      await this.$axios.put(`/books/${bookId}/cards/order`, {
        card_ids: payload
      })
      commit('setCards', cards)
    } catch (e) {
      console.log(e)
    }
  },
  async deleteCard ({}, { bookId, cardId }) {
    try {
      const { status } =
        await this.$axios.delete(`/books/${bookId}/cards/${cardId}`)
      return status
    } catch (e) {
      return e.response.status
    }
  }
}
