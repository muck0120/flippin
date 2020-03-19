<template>
  <div :class="$style.wrap">
    <HeaderBook
      :is-footer="false"
      :is-description="false"
      :back-to="`/books/${bookId}/cards`"
    />
    <HeaderCard
      @confirmDelete="isOpenModalDelete = true"
      :class="$style.question"
    />
    <FooterCard
      :class="$style.answer"
    />
    <FooterCardButtons
      :prevTo="prevCardTo"
      :nextTo="nextCardTo"
      :compTo="''"
      :class="$style.buttons"
    />

    <!-- 問題削除の確認モーダル -->
    <Modal
      v-if="isOpenModalDelete"
      @close="isOpenModalDelete = false"
    >
      <template #title>
        本当に削除しますか？
      </template>
      <template #desc>
        この操作は取り消せません。
      </template>
      <template #button>
        <ModalButtonTwo
          label-proceed="削除"
          label-cancel="キャンセル"
          @proceed="deleteCard()"
          @cancel="isOpenModalDelete = false"
        />
      </template>
    </Modal>

    <!-- 通信エラー時のモーダル -->
    <Modal
      v-if="isOpenModalError"
      @close="isOpenModalError = false"
    >
      <template #title>
        通信エラー
      </template>
      <template #desc>
        エラーが発生しました。もう一度お試し下さい。
      </template>
      <template #button>
        <ModalButtonOne
          @proceed="isOpenModalError = false"
        />
      </template>
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import HeaderBook from '@/components/TheHeaderBook.vue'
import HeaderCard from '@/components/TheHeaderCard.vue'
import FooterCard from '@/components/TheFooterCard.vue'
import FooterCardButtons from '@/components/TheFooterCardButtons.vue'
import Modal from '@/components/Modal'
import ModalButtonOne from '@/components/ModalButtonOne'
import ModalButtonTwo from '@/components/ModalButtonTwo'

export default {
  validate ({ params }) {
    return (
      /^\d+$/.test(params.bookId) &&
      /^\d+$/.test(params.cardId)
    )
  },
  async asyncData ({ store, params, error }) {
    const status = await store.dispatch(
      'book/fetchBook', { bookId: params.bookId, cardId: params.cardId }
    )
    if (status !== 200) error(status, 'error')
  },
  head () {
    return {
      title: this.card.card_question
    }
  },
  components: {
    HeaderBook,
    HeaderCard,
    FooterCard,
    FooterCardButtons,
    Modal,
    ModalButtonOne,
    ModalButtonTwo
  },
  watchQuery: true,
  data () {
    return {
      isOpenModalDelete: false,
      isOpenModalError: false
    }
  },
  computed: {
    bookId () {
      return this.$route.params.bookId
    },
    cardId () {
      return this.$route.params.cardId
    },
    prevCardTo () {
      const index = this.cards.findIndex(card => card === this.card)
      const prevCard = this.cards[index - 1]
      if (!prevCard) return ''
      return `/books/${this.bookId}/cards/${prevCard.card_id}`
    },
    nextCardTo () {
      const index = this.cards.findIndex(card => card === this.card)
      const nextCard = this.cards[index + 1]
      if (!nextCard) return ''
      return `/books/${this.bookId}/cards/${nextCard.card_id}`
    },
    ...mapState('book', ['book']),
    ...mapState('card', ['cards', 'card'])
  },
  methods: {
    async deleteCard () {
      this.$nuxt.$loading.start()
      const status = await this.$store.dispatch(
        'card/deleteCard', { bookId: this.bookId, cardId: this.cardId }
      )
      if (status === 200) {
        this.$router.push(`/books/${this.bookId}/cards`)
      } else {
        this.isOpenModalError = true
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

.question {
  margin-top: 30px;
}

.answer {
  margin-top: 30px;
}

.buttons {
  margin-top: 30px;
}
</style>
