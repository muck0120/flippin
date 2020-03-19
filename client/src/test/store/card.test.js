import Vuex from 'vuex'
import * as cardStore from '@/store/card'
import { createLocalVue } from '@vue/test-utils'
import axios from 'axios'
import clonedeep from 'lodash.clonedeep'

const localVue = createLocalVue()
localVue.use(Vuex)

jest.mock('axios')

describe('store/card.js', () => {
  let store
  let card
  let cardFormData

  beforeEach(() => {
    store = new Vuex.Store(clonedeep(cardStore))
    card = clonedeep({
      card_id: 1,
      book_id: 1,
      card_order: 1,
      card_question: 'モック用の問題文です。',
      card_question_image: 'mockQuestionImage.jpg',
      card_explanation: 'モック用の解説文です。',
      card_explanation_image: 'mockExplanationImage.jpg',
      card_created_at: '2020-03-15 11:19:55',
      card_updated_at: '2020-03-15 11:19:55'
    })
    cardFormData = clonedeep({
      question: 'モック用の問題文です。',
      questionImageUpload: 'モック用の問題画像Blobデータです。',
      choices: [
        {
          card_choice_text: 'モック用の選択肢1です。',
          card_choice_is_correct: true
        },
        {
          card_choice_text: 'モック用の選択肢2です。',
          card_choice_is_correct: false
        }
      ],
      explanation: 'モック用の解説文です。',
      explanationImageUpload: 'モック用の解説画像Blobデータです。',
    })
  })

  describe('mutations', () => {
    test('setCards を commit すると cards ステートに cards がセットされる', () => {
      expect(store.state.cards).toEqual([])
      store.commit('setCards', [card])
      expect(store.state.cards).toEqual([card])
    })

    test('setCard を commit すると card ステートに card がセットされる', () => {
      expect(store.state.card).toBeNull()
      store.commit('setCard', card)
      expect(store.state.card).toEqual(card)
    })
  })

  describe('actions', () => {
    beforeEach(() => {
      store.$axios = axios
    })

    describe('fetchCardImage を dispatch する', () => {
      beforeEach(() => {
        const response = {
          status: 200,
          data: 'This is Blob data.',
          headers: {
            'content-type': 'image/jpeg'
          }
        }
        axios.get.mockResolvedValue(response)
      })

      test('response データが return される', async () => {
        const { status, data, headers } = await store.dispatch('fetchCardImage', {
          cardId: card.card_id, fileName: card.card_question_image
        })
        expect(status).toEqual(200)
        expect(data).toEqual('This is Blob data.')
        expect(headers['content-type']).toEqual('image/jpeg')
      })
    })

    describe('storeCard を dispatch する', () => {
      beforeEach(() => {
        const response = { status: 200 }
        axios.post.mockResolvedValue(response)
      })

      test('status code 200 が return される', async () => {
        const status = await store.dispatch('storeCard', {
          bookId: card.book_id, payload: cardFormData
        })
        expect(status).toEqual(200)
      })
    })

    describe('updateCard を dispatch する', () => {
      beforeEach(() => {
        const response = { status: 200 }
        axios.post.mockResolvedValue(response)
      })

      test('status code 200 が return される', async () => {
        const status = await store.dispatch('updateCard', {
          bookId: card.book_id, cardId: card.card_id, payload: cardFormData
        })
        expect(status).toEqual(200)
      })
    })

    describe('deleteCard を dispatch する', () => {
      beforeEach(() => {
        const response = { status: 200 }
        axios.delete.mockResolvedValue(response)
      })

      test('status code 200 が return される', async () => {
        const status = await store.dispatch('deleteCard', {
          bookId: card.book_id, cardId: card.card_id
        })
        expect(status).toEqual(200)
      })
    })
  })
})
