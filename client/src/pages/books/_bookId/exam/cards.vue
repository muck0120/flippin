<template>
  <div :class="$style.wrap">
    <HeaderBook
      title="AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1"
      desc="ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。ここに説明が入ります。"
      :is-footer="false"
    />
    <div :class="$style.cards">
      <div
        v-for="n in 10"
        :key="n"
        :class="$style.card"
      >
        <ListCard />
      </div>
    </div>
    <div :class="$style.buttons">
      <FooterCardButtons />
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'

import HeaderBook from '@/components/TheHeaderBook.vue'
import ListCard from '@/components/ListCard.vue'
import FooterCardButtons from '@/components/TheFooterCardButtons.vue'

export default Vue.extend({
  components: {
    HeaderBook,
    ListCard,
    FooterCardButtons
  }
})
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
