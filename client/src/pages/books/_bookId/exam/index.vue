<template>
  <div>
   <HeaderBook :is-footer="false" />
    <h2 :class="$style.title">
      このテストを開始しますか？
    </h2>
    <div :class="$style.check">
      <label>
        <input
          type="checkbox"
          v-model="isRandom"
          :class="$style.check_box"
        >
        <span :class="$style.check_text">
          ランダム形式で出題
        </span>
      </label>
    </div>
    <div :class="$style.buttons">
      <button
        @click="startExam()"
        :class="[$style.button, $style.red]"
      >
        スタート
        <img
          src="@/assets/images/arrow-white.svg"
          :class="$style.icon"
        >
      </button>
      <NLink
        :to="`/books/${book.book_id}/cards`"
        :class="$style.button"
      >
        キャンセル
      </NLink>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import clonedeep from 'lodash.clonedeep'
import HeaderBook from '@/components/TheHeaderBook.vue'

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
  head () {
    return {
      title: `テスト開始 | ${this.book.book_title}`
    }
  },
  components: {
    HeaderBook
  },
  data () {
    return {
      isRandom: false
    }
  },
  computed: {
    ...mapState('book', ['book']),
    ...mapState('card', ['cards'])
  },
  methods: {
    startExam () {
      let exam = clonedeep(this.cards)
      if (!this.isRandom) {
        exam = exam.map(card => {
          return { cardId: card.card_id, choicedId: null }
        })
      } else {
        exam = this.shuffle(exam).map(card => {
          return { cardId: card.card_id, choicedId: null }
        })
      }
      this.$store.dispatch('exam/storeExam', { exam })
      this.$router.push(`/books/${this.book.book_id}/exam/${exam[0].cardId}`)
    },
    shuffle (array) {
      for(var i = array.length - 1; i > 0; i--){
          var r = Math.floor(Math.random() * (i + 1))
          var tmp = array[i]
          array[i] = array[r]
          array[r] = tmp
      }
      return array
    }
  }
}
</script>

<style lang="scss" module>
.title {
  font-size: 40px;
  font-weight: bold;
  text-align: center;
  margin-top: 50px;

  @include mq(tb) {
    font-size: 31px;
  }

  @include mq(sp) {
    font-size: 22px;
  }
}

.check {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 50px;

  @include mq(tb) {
    margin-top: 45px;
  }

  @include mq(sp) {
    margin-top: 40px;
  }
}

.check_text {
  padding-left: 30px;
  font-size: 18px;
  font-weight: bold;
  position: relative;
  transition: all 0.3s;

  @include mq(tb) {
    padding-left: 27px;
    font-size: 16px;
  }

  @include mq(sp) {
    padding-left: 24px;
    font-size: 14px;
  }
}

.check_text::before {
  $square-pc: 25px;
  $square-tb: 22px;
  $square-sp: 19px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  left: 0;
  background-image: url('~assets/images/check-off.svg');
  background-size: contain;
  background-position: center center;
  background-repeat: no-repeat;
  transition: all 0.3s;

  @include mq(tb) {
    width: $square-tb;
    height: $square-tb;
    top: calc(50% - #{$square-tb} / 2);
  }

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
  }
}

.check_box:checked + .check_text::before {
  background-image: url('~assets/images/check-on.svg');
  transition: all 0.3s;
}

.buttons {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 100px;

  @include mq(tb) {
    margin-top: 75px;
  }

  @include mq(sp) {
    margin-top: 50px;
  }
}

.button {
  $height-pc: 50px;
  $height-tb: 45px;
  $height-sp: 40px;

  width: 250px;
  height: $height-pc;
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: $height-pc / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #00b8a9;
  font-size: 18px;
  font-weight: bold;
  transition: all 0.3s;
  white-space: nowrap;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  position: relative;

  @include mq(tb) {
    width: 200px;
    height: $height-tb;
    border-radius: $height-tb / 2;
    font-size: 16px;
  }

  @include mq(sp) {
    width: 140px;
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 13px;
  }
}

.button.red {
  background-color: #f6416c;
  border: 1px solid #f6416c;
  color: #fff;
}

.button + .button {
  margin-left: 20px;

  @include mq(sp) {
    margin-left: 10px;
  }
}

.icon {
  $square-pc: 20px;
  $square-tb: 16px;
  $square-sp: 12px;

  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  right: 15px;
  transition: all 0.3s;

  @include mq(tb) {
    width: $square-tb;
    height: $square-tb;
    top: calc(50% - #{$square-tb} / 2);
  }

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    right: 10px;
  }
}

.button:hover {
  box-shadow: none;
  transition: all 0.3s;
}

.button.red:hover .icon {
  transform: translateX(5px);
  transition: all 0.3s;
}
</style>
