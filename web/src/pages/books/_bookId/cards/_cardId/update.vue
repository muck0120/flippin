<template>
  <div :class="$style.wrap">
    <HeaderBook
      :is-description="false"
      :is-footer="false"
      :back-to="`/books/${bookId}/cards`"
    />
    <FormCard
      :question.sync="form.question"
      :questionImage.sync="form.questionImage"
      :choices.sync="form.choices"
      :explanation.sync="form.explanation"
      :explanationImage.sync="form.explanationImage"
      @questionImageUpload="$event => form.questionImageUpload = $event.file"
      @explanationImageUpload="$event => form.explanationImageUpload = $event.file"
      @addChoice="addChoice()"
      @submit="updateCard()"
      @cancel="$router.push(`/books/${bookId}/cards/${cardId}`)"
      :class="$style.body"
    />
    <Modal
      v-if="isOpenModal"
      @close="isOpenModal = false"
    >
      <template #title>
        通信エラー
      </template>
      <template #desc>
        エラーが発生しました。もう一度お試し下さい。
      </template>
      <template #button>
        <ModalButtonOne @proceed="isOpenModal = false" />
      </template>
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import clonedeep from 'lodash.clonedeep'
import HeaderBook from '@/components/TheHeaderBook.vue'
import FormCard from '@/components/TheFormCard.vue'
import Modal from '@/components/Modal'
import ModalButtonOne from '@/components/ModalButtonOne'

const choice = {
  card_choice_text: '',
  card_choice_is_correct: false
}

export default {
  validate ({ params }) {
    return (
      /^\d+$/.test(params.bookId) &&
      /^\d+$/.test(params.cardId)
    )
  },
  async asyncData ({ app, store, params, error }) {
    const status = await store.dispatch(
      'book/fetchBook', { bookId: params.bookId, cardId: params.cardId }
    )
    if (status !== 200) {
      error(status, 'error')
      return false
    }
    const user = store.state.user.user
    const book = store.state.book.book
    if (user.user_id !== book.user_id) {
      error(401, 'Unauthorized')
      return false
    }
    const card = clonedeep(store.state.card.card)
    return {
      form: {
        question: card.card_question,
        questionImage: '',
        questionImageUpload: null,
        choices: card.card_choices,
        explanation: card.card_explanation || '',
        explanationImage: '',
        explanationImageUpload: null
      }
    }
  },
  async mounted () {
    this.form.questionImageUpload =
      await this.createFileObject(this.card.card_question_image)
    this.form.explanationImageUpload =
      await this.createFileObject(this.card.card_explanation_image)
    if (this.form.questionImageUpload) {
      this.form.questionImage =
        await this.createImage(this.form.questionImageUpload)
    }
    if (this.form.explanationImageUpload) {
      this.form.explanationImage =
        await this.createImage(this.form.explanationImageUpload)
    }
  },
  head () {
    return {
      title: '問題の更新'
    }
  },
  middleware: ['authenticated'],
  components: {
    HeaderBook,
    FormCard,
    Modal,
    ModalButtonOne
  },
  data () {
    return {
      isOpenModal: false
    }
  },
  computed: {
    bookId () {
      return this.$route.params.bookId
    },
    cardId () {
      return this.$route.params.cardId
    },
    ...mapState('card', ['card'])
  },
  methods: {
    createFileObject (fileName) {
      return new Promise(async resolve => {
        if (!fileName) {
          resolve(null)
          return false
        }
        const { status, data: blob, headers } = await this.$store.dispatch(
          'card/fetchCardImage', { cardId: this.card.card_id, fileName }
        )
        if (status !== 200) {
          resolve(null)
          return false
        }
        const file = new File([blob], fileName, {
          type: headers['content-type']
        })
        resolve(file)
      })
    },
    createImage (file) {
      return new Promise(resolve => {
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = e => resolve(e.target.result)
      })
    },
    addChoice () {
      this.form.choices.push(clonedeep(choice))
    },
    async updateCard () {
      this.$nuxt.$loading.start()
      const status = await this.$store.dispatch('card/updateCard',
        { bookId: this.bookId, cardId: this.cardId, payload: this.form }
      )
      if (status === 200) {
        this.$router.push(`/books/${this.bookId}/cards/${this.cardId}`)
      } else {
        this.isOpenModal = true
      }
      this.$nuxt.$loading.finish()
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
}

.body {
  margin-top: 30px;
}
</style>
