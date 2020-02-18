<template>
  <div :class="$style.wrap">
    <p :class="$style.heading">
      お気に入りがありません
    </p>
    <p :class="$style.text">
      条件に合致する「お気に入り」がありません。<br>
      問題集の「いいね」をクリックすると<br :class="$style.sp">お気に入りに登録できます。
    </p>
    <div :class="$style.example">
      <span :class="[$style.favorite, $style.before]">
        123いいね
      </span>
      <fa
        :icon="faLongArrowAltRight"
        :class="$style.arrow"
      />
      <span :class="[$style.favorite, $style.after]">
        124いいね
      </span>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import { faLongArrowAltRight } from '@fortawesome/free-solid-svg-icons'

export default Vue.extend({
  computed: {
    faLongArrowAltRight () {
      return faLongArrowAltRight
    }
  }
})
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
}

.heading {
  font-size: 40px;
  font-weight: bold;
  text-align: center;
  line-height: 1.5;

  @include mq(tb) {
    font-size: 33px;
  }

  @include mq(sp) {
    font-size: 26px;
  }
}

.text {
  margin-top: 20px;
  font-size: 16px;
  font-weight: bold;
  text-align: center;
  line-height: 1.5;

  @include mq(tb) {
    font-size: 15px;
  }

  @include mq(sp) {
    font-size: 14px;
  }
}

.sp {
  display: none;

  @include mq(sp) {
    display: block;
  }
}

.example {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 50px;

  @include mq(tb) {
    margin-top: 40px;
  }

  @include mq(sp) {
    margin-top: 30px;
  }
}

.favorite {
  font-size: 24px;
  padding-left: 35px;
  white-space: nowrap;
  font-weight: bold;
  position: relative;

  @include mq(tb) {
    font-size: 20px;
    padding-left: 30px;
  }

  @include mq(sp) {
    font-size: 16px;
    padding-left: 25px;
  }
}

.favorite::before {
  $square-pc: 30px;
  $square-tb: 25px;
  $square-sp: 20px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  left: 0;
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center center;

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

.favorite.before {
  color: #999;
}

.favorite.before::before {
  background-image: url('~assets/images/heart-empty-gray.svg');
}

.favorite.after {
  color: #f6416c;
}

.favorite.after::before {
  background-image: url('~assets/images/heart-active.svg');
}

.arrow {
  font-size: 50px;
  margin: 0 30px;

  @include mq(tb) {
    margin: 0 25px;
  }

  @include mq(sp) {
    margin: 0 20px;
  }
}
</style>
