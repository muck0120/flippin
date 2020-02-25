<template>
  <div>
    <HeaderBook
      title="AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1"
      :is-footer="false"
      back-to="/books/1/exam"
    />
    <HeaderCard :class="$style.question" />
    <FooterCardButtons :class="$style.buttons" />
  </div>
</template>

<script lang="ts">
import Vue from 'vue'

import HeaderBook from '@/components/TheHeaderBook.vue'
import HeaderCard from '@/components/TheHeaderCard.vue'
import FooterCardButtons from '@/components/TheFooterCardButtons.vue'

export default Vue.extend({
  components: {
    HeaderBook,
    HeaderCard,
    FooterCardButtons
  }
})
</script>

<style lang="scss" module>
.question {
  margin-top: 30px;
}

.buttons {
  margin-top: 30px;
}
</style>
