<template>
  <div :class="$style.wrap">
    <HeaderBook
      :is-footer="false"
      :back-to="''"
    />
    <div :class="$style.cards">
      <div
        v-for="(card, index) in sortedCards"
        :key="card.card_id"
        :class="$style.card"
      >
        <ListCard
          :to="`/books/${book.book_id}/exam/${card.card_id}`"
          :index="index"
          :card="card"
          :isChoiced="isChoiced(card.card_id)"
        />
      </div>
    </div>
    <div :class="$style.buttons">
      <FooterCardButtons
        :prevTo="''"
        :nextTo="''"
        :compTo="`/books/${book.book_id}/exam/result`"
        :class="$style.buttons"
      />
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import clonedeep from 'lodash.clonedeep'
import HeaderBook from '@/components/TheHeaderBook.vue'
import ListCard from '@/components/ListCard.vue'
import FooterCardButtons from '@/components/TheFooterCardButtons.vue'

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
    this.$examGateway()
  },
  head () {
    return {
      title: `テスト | ${this.book.book_title}`
    }
  },
  components: {
    HeaderBook,
    ListCard,
    FooterCardButtons
  },
  computed: {
    sortedCards () {
      let cards = clonedeep(this.cards)
      cards = this.exam.map(item => {
        let value
        cards.forEach(card => {
          if (card.card_id === item.cardId) value = card
        })
        return value
      })
      return cards
    },
    ...mapState('book', ['book']),
    ...mapState('card', ['cards']),
    ...mapState('exam', ['exam'])
  },
  methods: {
    isChoiced (cardId) {
      const target = this.exam.find(exam => exam.cardId === cardId)
      return Boolean(target.choicedId)
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
}

.cards {
  margin-top: 30px;
}

.card {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card + .card {
  margin-top: 10px;
}

.buttons {
  width: 1000px;
  position: fixed;
  bottom: 80px;

  @include mq(tb) {
    width: calc(100% - 40px);
    bottom: 70px;
  }

  @include mq(sp) {
    width: calc(100% - 20px);
    bottom: 30px;
  }
}
</style>
