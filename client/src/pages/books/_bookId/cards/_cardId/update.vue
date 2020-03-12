<template>
  <div :class="$style.wrap">
    <HeaderBook
      title="AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1、AWS問題集その1"
      :is-footer="false"
      back-to="/books/1/cards"
    />
    <FormCard :class="$style.body" />
  </div>
</template>

<script>
import HeaderBook from '@/components/TheHeaderBook.vue'
import FormCard from '@/components/TheFormCard.vue'

export default {
  components: {
    HeaderBook,
    FormCard
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
}

.body {
  margin-top: 30px;
}
</style>
