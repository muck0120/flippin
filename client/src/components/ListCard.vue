<template>
  <NLink
    :to="to"
    :class="[
      $style.wrap,
      { [$style.choiced]: isChoiced }
    ]"
  >
    <div :class="[
      $style.number,
      { [$style.choiced]: isChoiced }
    ]">
      {{ index + 1 }}
    </div>
    <p :class="[
      $style.title,
      { [$style.choiced]: isChoiced }
    ]">
      {{ card.card_question }}
    </p>
  </NLink>
</template>

<script>
export default {
  props: {
    index: {
      type: Number,
      required: true
    },
    card: {
      type: Object,
      required: true
    },
    isChoiced: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  computed: {
    to () {
      if (!this.$route.path.match('exam')) {
        return `/books/${this.card.book_id}/cards/${this.card.card_id}`
      } else {
        return `/books/${this.card.book_id}/exam/${this.card.card_id}`
      }
    }
  }
}
</script>

<style lang="scss" module>
$height: 50px;

.wrap {
  width: 100%;
  min-width: 0;
  height: $height;
  padding-right: 40px;
  border: 3px solid #00b8a9;
  border-radius: 10px;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  background-color: #fff;
  cursor: pointer;
  position: relative;

  @include mq(tb) {
    padding-right: 35px;
  }

  @include mq(sp) {
    padding-right: 30px;
  }
}

.wrap.choiced {
  border: 3px solid #999;
}

.wrap::after {
  $square-pc: 18px;
  $square-tb: 16px;
  $square-sp: 14px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  right: 15px;
  background-image: url('~assets/images/arrow-green.svg');
  background-size: contain;
  background-position: center center;
  background-repeat: no-repeat;
  transition: all 0.3s;

  @include mq(tb) {
    width: $square-tb;
    height: $square-tb;
    top: calc(50% - #{$square-tb} / 2);
    right: 10px;
  }

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    right: 10px;
  }
}

.wrap.choiced::after {
  background-image: url('~assets/images/arrow-gray.svg');
}

.wrap:hover::after {
  transform: translateX(5px);
  transition: all 0.3s;
}

.number {
  width: $height;
  height: $height;
  border-radius: 10px 0 0 10px;
  background-color: #00b8a9;
  color: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 20px;
  font-weight: bold;

  @include mq(tb) {
    font-size: 18px;
  }

  @include mq(sp) {
    font-size: 16px;
  }
}

.number.choiced {
  background-color: #999;
}

.title {
  width: 100%;
  margin-left: 15px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;

  @include mq(tb) {
    margin-left: 10px;
  }
}

.title.choiced {
  color: #999;
}
</style>
