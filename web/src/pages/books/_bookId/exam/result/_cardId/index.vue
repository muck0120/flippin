<template>
  <div :calss="$style.wrap">
    <HeaderBook
      :is-footer="false"
      :is-description="false"
      :back-to="`/books/${book.book_id}/exam/result`"
    />
    <HeaderCard :class="$style.question" />
    <FooterCard :class="$style.answer" />
    <client-only>
      <FooterCardButtons
        :prevTo="prevCardTo"
        :nextTo="nextCardTo"
        :compTo="''"
        :class="$style.buttons"
      />
    </client-only>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import HeaderBook from '@/components/TheHeaderBook.vue'
import HeaderCard from '@/components/TheHeaderCard.vue'
import FooterCard from '@/components/TheFooterCard.vue'
import FooterCardButtons from '@/components/TheFooterCardButtons.vue'

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
  created () {
    this.$examGateway()
  },
  head () {
    return {
      title: `テスト結果 | 回答 | ${this.card.card_question}`
    }
  },
  components: {
    HeaderBook,
    HeaderCard,
    FooterCard,
    FooterCardButtons
  },
  computed: {
    bookId () {
      return parseInt(this.$route.params.bookId)
    },
    cardId () {
      return parseInt(this.$route.params.cardId)
    },
    prevCardTo () {
      if (this.exam.length === 0) return ''
      const index = this.exam.findIndex(card => {
        return card.cardId === this.cardId
      })
      const prevCard = this.exam[index - 1]
      if (!prevCard) return ''
      return `/books/${this.bookId}/exam/result/${prevCard.cardId}`
    },
    nextCardTo () {
      if (this.exam.length === 0) return ''
      const index = this.exam.findIndex(card => {
        return card.cardId === this.cardId
      })
      const nextCard = this.exam[index + 1]
      if (!nextCard) return ''
      return `/books/${this.bookId}/exam/result/${nextCard.cardId}`
    },
    ...mapState('book', ['book']),
    ...mapState('card', ['card']),
    ...mapState('exam', ['exam'])
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
