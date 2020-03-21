<template>
  <div :class="$style.wrap">
    <div :class="$style.header">
      <h2
        v-if="!$route.path.match('exam/result')"
        :class="$style.title"
      >
        問題{{ cardIndex + 1 }}
      </h2>
      <h2
        v-else-if="$route.path.match('exam/result')"
        :class="[$style.title, $style.result]"
      >
        <client-only>
          <img
            v-if="choicedId === correctId"
            src="@/assets/images/result-correct.svg"
            alt="正解"
            :class="$style.result_icon"
          >
          <img
            v-else-if="choicedId !== correctId"
            src="@/assets/images/result-incorrect.svg"
            alt="不正解"
            :class="$style.result_icon"
          >
          <span :class="[
            $style.result_title,
            { [$style.red]: choicedId !== correctId }
          ]">
            問題{{ cardIndex + 1 }}
          </span>
        </client-only>
      </h2>
      <template v-if="
        user && user.user_id === book.user_id &&
        !this.$route.path.match('exam')
      ">
        <NLink
          :to="`/books/${bookId}/cards/${cardId}/update`"
          :class="$style.icon"
        >
          <fa :icon="faEdit" />
        </NLink>
        <div
          @click="$emit('confirmDelete')"
          :class="$style.icon"
        >
          <fa :icon="faTrash" />
        </div>
      </template>
    </div>
    <p
      :class="$style.question"
    >{{ card.card_question }}</p>
    <img
      v-if="card.card_question_image"
      :src="imageUrl"
      :class="$style.image"
    >
    <span :class="$style.border"><!-- border --></span>
    <label
      v-for="choice in choices"
      :key="choice.card_choice_id"
      @click="storeChoicedId(choice.card_choice_id)"
      :class="[
        $style.choice,
        { [$style.disabled]: $route.path.match('exam/result') },
        { [$style.choiced]: $route.path.match('exam/result') &&
          choicedId === choice.card_choice_id }
      ]"
    >
      <input
        type="radio"
        name="choice"
        :checked="
          $route.path.match('exam') &&
          choicedId === choice.card_choice_id
        "
        :class="$style.check"
      >
      <p
        :class="$style.text"
      >{{ choice.card_choice_text }}</p>
    </label>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import clonedeep from 'lodash.clonedeep'
import { faEdit, faTrash } from '@fortawesome/free-solid-svg-icons'

export default {
  computed: {
    faEdit () {
      return faEdit
    },
    faTrash () {
      return faTrash
    },
    imageUrl () {
      const url = process.env.BASE_URL_ASSETS
      const cardId = this.cardId
      const filename = this.card.card_question_image
      return `${url}/images/cards/${this.card.card_id}/${filename}`
    },
    bookId () {
      return this.$route.params.bookId
    },
    cardId () {
      return this.$route.params.cardId
    },
    cardIndex () {
      if (!this.$route.path.match('exam')) {
        return this.cards.findIndex(card => card === this.card)
      } else {
        return this.exam.findIndex(item => item.cardId === this.card.card_id)
      }
    },
    choices () {
      return this.card.card_choices
    },
    choicedId () {
      if (process.server) return
      const choiced = this.exam.find(item => item.cardId === this.card.card_id)
      return choiced.choicedId
    },
    correctId () {
      const correct = this.choices.find(choice => choice.card_choice_is_correct)
      return correct.card_choice_id
    },
    ...mapState('user', ['user']),
    ...mapState('book', ['book']),
    ...mapState('card', ['cards', 'card']),
    ...mapState('exam', ['exam'])
  },
  methods: {
    storeChoicedId (choicedId) {
      if (!this.$route.path.match('exam')) return false
      let exam = clonedeep(this.exam)
      exam = exam.map(item => {
        if (item.cardId === this.card.card_id) {
          return { cardId: item.cardId, choicedId: choicedId }
        } else {
          return item
        }
      })
      this.$store.dispatch('exam/storeExam', { exam })
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: 10px;
  padding: 30px;

  @include mq(tb) {
    padding: 25px;
  }

  @include mq(sp) {
    padding: 20px;
  }
}

.header {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.title {
  font-size: 20px;
  font-weight: bold;
  white-space: nowrap;

  @include mq(tb) {
    font-size: 18px;
  }

  @include mq(sp) {
    font-size: 16px;
  }
}

.title.result {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.result_icon {
  width: auto;
  height: 25px;

  @include mq(tb) {
    height: 20px;
  }

  @include mq(sp) {
    height: 20px;
  }
}

.result_title {
  margin-left: 10px;
}

.result_title.red {
  color: #f6416c;
}

.icon {
  color: #999;
  font-size: 25px;
  margin-left: auto;
  cursor: pointer;
  transition: color 0.3s;

  @include mq(tb) {
    font-size: 23px;
  }

  @include mq(sp) {
    font-size: 20px;
  }
}

.icon + .icon {
  margin-left: 20px;
}

.icon:hover {
  color: #00b8a9;
  transition: color 0.3s;
}

.question {
  margin-top: 30px;
  line-height: 1.5;
  white-space: pre-wrap;

  @include mq(tb) {
    margin-top: 25px;
  }

  @include mq(sp) {
    margin-top: 20px;
  }
}

.image {
  margin-top: 30px;
  max-width: 100%;

  @include mq(tb) {
    margin-top: 25px;
  }

  @include mq(sp) {
    margin-top: 20px;
  }
}

.border {
  display: block;
  height: 3px;
  margin: 30px 0;
  position: relative;

  @include mq(tb) {
    margin: 25px 0;
  }

  @include mq(sp) {
    margin: 20px 0;
  }
}

.border::before {
  content: '';
  background-image: linear-gradient(to right, #00b8a9, #00b8a9 5px, transparent 5px, transparent 8px);
  background-size: 8px 3px;
  background-repeat: repeat-x;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
}

.choice {
  display: block;
  cursor: pointer;
}

.choice.disabled {
  pointer-events: none;
}

.choice.choiced {
  font-weight: bold;
}

.choice + .choice {
  margin-top: 20px;

  @include mq(tb) {
    margin-top: 15px;
  }

  @include mq(sp) {
    margin-top: 10px;
  }
}

.text {
  line-height: 1.5;
  padding-left: 40px;
  position: relative;
  white-space: pre-wrap;

  @include mq(tb) {
    padding-left: 36px;
  }

  @include mq(sp) {
    padding-left: 32px;
  }
}

.text::before {
  $square-pc: 30px;
  $square-tb: 26px;
  $square-sp: 22px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  left: 0;
  border: 3px solid #00b8a9;
  border-radius: $square-pc / 2;

  @include mq(tb) {
    width: $square-tb;
    height: $square-tb;
    top: calc(50% - #{$square-tb} / 2);
    border-radius: $square-tb / 2;
  }

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    border-radius: $square-sp / 2;
  }
}

.check:checked + .text::after {
  $square-pc: 16px;
  $square-tb: 12px;
  $square-sp: 8px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  left: 7px;
  background-color: #00b8a9;
  border-radius: $square-pc / 2;

  @include mq(tb) {
    width: $square-tb;
    height: $square-tb;
    top: calc(50% - #{$square-tb} / 2);
    border-radius: $square-tb / 2;
  }

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    border-radius: $square-sp / 2;
  }
}
</style>
