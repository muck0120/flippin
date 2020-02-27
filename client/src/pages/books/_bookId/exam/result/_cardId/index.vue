<template>
  <div>
    <HeaderBook
      title="AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1"
      :is-footer="false"
      back-to="/books/1/cards"
    />
    <HeaderCard :class="$style.question" />
    <FooterCard :class="$style.answer" />
    <FooterCardButtons :class="$style.buttons" />
  </div>
</template>

<script>
import HeaderBook from '@/components/TheHeaderBook.vue'
import HeaderCard from '@/components/TheHeaderCard.vue'
import FooterCard from '@/components/TheFooterCard.vue'
import FooterCardButtons from '@/components/TheFooterCardButtons.vue'

export default {
  components: {
    HeaderBook,
    HeaderCard,
    FooterCard,
    FooterCardButtons
  }
}
</script>

<style lang="scss" module>
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
