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
  async storeCard ({}, { bookId, payload }) {
    try {
      const formData = new FormData()
      formData.append('card_question', payload.question)
      formData.append('card_question_image', payload.questionImageUpload || '')
      payload.choices.forEach((choice, index) => {
        formData.append(
          `card_choices[${index}][card_choice_id]`,
          choice.card_choice_id || ''
        )
        formData.append(
          `card_choices[${index}][card_choice_text]`,
          choice.card_choice_text
        )
        formData.append(
          `card_choices[${index}][card_choice_is_correct]`,
          choice.card_choice_is_correct ? 1 : 0
        )
      })
      formData.append('card_explanation', payload.explanation)
      formData.append('card_explanation_image', payload.explanationImageUpload || '')
      const { status } = await this.$axios.post(`/books/${bookId}/cards/card`,
        formData, { headers: { 'content-type': 'multipart/form-data' } }
      )
      return status
    } catch (e) {
      return e.response.status
    }
  },
  async updateCardOrder ({}, { bookId, payload }) {
    try {
      await this.$axios.put(`/books/${bookId}/cards/order`, {
        card_ids: payload
      })
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
