<template>
  <div :class="$style.wrap">
    <HeaderBook
      title="AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1"
      desc="ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。"
      :is-footer="false"
    />
    <p :class="$style.desc">
      お疲れさまでした！あなたの点数は…<br>
      23/50問中（正解率45.3%）でした。
    </p>
    <div :class="$style.cards">
      <div
        v-for="n in 10"
        :key="n"
        :class="$style.card"
      >
        <div
          v-if="true"
          :class="$style.result"
        >
          <img
            v-if="true"
            src="@/assets/images/result-correct.svg"
            alt="正解"
          >
          <img
            v-if="false"
            src="@/assets/images/result-incorrect.svg"
            alt="不正解"
          >
        </div>
        <ListCard />
      </div>
    </div>
    <div :class="$style.buttons">
      <button
        :class="[
          $style.button,
          $style.green
        ]"
      >
        問題一覧へ戻る
      </button>
      <button :class="$style.button">
        もう一度チャレンジ
      </button>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'

import HeaderBook from '@/components/TheHeaderBook.vue'
import ListCard from '@/components/ListCard.vue'

export default Vue.extend({
  components: {
    HeaderBook,
    ListCard
  }
})
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
}

.desc {
  margin-top: 50px;
  font-size: 40px;
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
  margin-top: 50px;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;

  @include mq(tb) {
    margin-top: 40px;
  }

  @include mq(sp) {
    margin-top: 30px;
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
  font-size: 18px;
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
