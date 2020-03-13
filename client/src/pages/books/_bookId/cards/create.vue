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
      @submit="storeCard()"
      @cancel="$router.push(`/books/${bookId}/cards`)"
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
import clonedeep from 'lodash.clonedeep'
import HeaderBook from '@/components/TheHeaderBook.vue'
import FormCard from '@/components/TheFormCard.vue'
import Modal from '@/components/Modal'
import ModalButtonOne from '@/components/ModalButtonOne'

const choice = {
  card_choice_id: null,
  card_choice_text: '',
  card_choice_is_correct: false
}

export default {
  validate ({ params }) {
    return /^\d+$/.test(params.bookId)
  },
  async asyncData ({ store, params, error }) {
    const status = await store.dispatch(
      'book/fetchBook', { bookId: params.bookId }
    )
    if (status !== 200) error(status, 'error')
  },
  created () {
    const choice1 = clonedeep(choice)
    choice1.card_choice_is_correct = true
    const choice2 = clonedeep(choice)
    this.form.choices.push(choice1)
    this.form.choices.push(choice2)
  },
  head () {
    return {
      title: '新規問題の作成'
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
      form: {
        question: '',
        questionImage: '',
        questionImageUpload: null,
        choices: [],
        explanation: '',
        explanationImage: '',
        explanationImageUpload: null
      },
      isOpenModal: false
    }
  },
  computed: {
    bookId () {
      return this.$route.params.bookId
    }
  },
  methods: {
    addChoice () {
      this.form.choices.push(clonedeep(choice))
    },
    async storeCard () {
      this.$nuxt.$loading.start()
      const status = await this.$store.dispatch('card/storeCard',
        { bookId: this.bookId, payload: this.form }
      )
      if (status === 200) {
        this.$router.push(`/books/${this.bookId}/cards`)
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
