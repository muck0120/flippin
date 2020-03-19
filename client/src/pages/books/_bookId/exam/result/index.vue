<template>
  <div :class="$style.wrap">
    <HeaderBook
      :is-footer="false"
      :back-to="''"
    />
    <client-only>
      <p :class="$style.desc">
        お疲れさまでした！あなたの点数は…<br>
        {{ correctNumber }}/{{ totalNumber }}問中（正解率{{ correctRate }}%）でした。
      </p>
    </client-only>
    <div :class="$style.cards">
      <div
        v-for="(card, index) in sortedCards"
        :key="card.card_id"
        :class="$style.card"
      >
        <div :class="$style.result">
          <img
            v-if="isCorrect(card)"
            src="@/assets/images/result-correct.svg"
            alt="正解"
          >
          <img
            v-else-if="!isCorrect(card)"
            src="@/assets/images/result-incorrect.svg"
            alt="不正解"
          >
        </div>
        <ListCard
          :to="`/books/${book.book_id}/exam/result/${card.card_id}`"
          :index="index"
          :card="card"
          :isIncorrect="!isCorrect(card)"
        />
      </div>
    </div>
    <div :class="$style.buttons">
      <NLink
        :to="`/books/${book.book_id}/cards`"
        :class="[$style.button, $style.green]"
      >
        問題一覧へ戻る
      </NLink>
      <NLink
        :to="`/books/${book.book_id}/exam`"
        :class="$style.button"
      >
        もう一度チャレンジ
      </NLink>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import clonedeep from 'lodash.clonedeep'
import HeaderBook from '@/components/TheHeaderBook.vue'
import ListCard from '@/components/ListCard.vue'

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
      title: `テスト結果 | ${this.book.book_title}`
    }
  },
  components: {
    HeaderBook,
    ListCard
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
    totalNumber () {
      return this.sortedCards.length
    },
    correctNumber () {
      if (process.server) return 0
      const exams = clonedeep(this.exam)
      const correctCards = this.cards.filter(card => {
        const { choicedId: choicedId } =
          exams.find(exam => exam.cardId === card.card_id)
        const { card_choice_id: correctId } =
          card.card_choices.find(choice => choice.card_choice_is_correct)
        return choicedId === correctId
      })
      return correctCards.length
    },
    correctRate () {
      if (process.server) return 0
      const rate = this.correctNumber / this.totalNumber * 100
      return rate.toFixed(1)
    },
    ...mapState('book', ['book']),
    ...mapState('card', ['cards']),
    ...mapState('exam', ['exam'])
  },
  methods: {
    isCorrect (card) {
      const exams = clonedeep(this.exam)
      const choiced = exams.find(exam => {
        return exam.cardId === card.card_id
      })
      const matched = card.card_choices.find(choice => {
        return choice.card_choice_id === choiced.choicedId
      })
      return Boolean(matched ? matched.card_choice_is_correct : false)
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
}

.desc {
  margin-top: 50px;
  font-size: 30px;
  font-weight: bold;
  line-height: 1.3;
  text-align: center;

  @include mq(tb) {
    margin-top: 40px;
    font-size: 30px;
  }

  @include mq(sp) {
    margin-top: 30px;
    font-size: 16px;
  }
}

.cards {
  margin-top: 50px;
}

.card {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card + .card {
  margin-top: 10px;
}

.result {
  width: 45px;
  height: 45px;
  margin-right: 10px;

  @include mq(tb) {
    width: 40px;
    height: 40px;
    margin-right: 10px;
  }

  @include mq(sp) {
    width: 35px;
    height: 35px;
    margin-right: 5px;
  }
}

.result > img {
  width: 100%;
  height: 100%;
}

.buttons {
  width: 1000px;
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  bottom: 30px;

  @include mq(tb) {
    width: calc(100% - 40px);
    bottom: 20px;
  }

  @include mq(sp) {
    width: calc(100% - 20px);
    bottom: 20px;
  }
}

.button {
  $height-pc: 50px;
  $height-tb: 45px;
  $height-sp: 40px;

  width: 200px;
  height: $height-pc;
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: $height-pc / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #00b8a9;
  font-size: 16px;
  font-weight: bold;
  transition: all 0.3s;
  white-space: nowrap;

  @include mq(tb) {
    height: $height-tb;
    border-radius: $height-tb / 2;
    font-size: 16px;
  }

  @include mq(sp) {
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 13px;
  }
}

.button:hover {
  opacity: 0.7;
  transition: all 0.3s;
}

.button.green {
  background-color: #00b8a9;
  color: #fff;
}

.button + .button {
  margin-left: 20px;

  @include mq(sp) {
    margin-left: 10px;
  }
}
</style>
