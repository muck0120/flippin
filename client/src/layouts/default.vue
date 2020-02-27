<template>
  <div>
    <header :class="$style.header">
      <HeaderApp />
    </header>
    <section :class="$style.content">
      <nuxt />
    </section>
  </div>
</template>

<script>
import HeaderApp from '@/components/TheHeaderApp.vue'

export default {
  components: {
    HeaderApp
  }
}
</script>

<style lang="scss" module>
$header-height-pc: 70px;
$header-height-tb: 60px;
$header-height-sp: 50px;

.header {
  width: 100%;
  height: $header-height-pc;

  @include mq(tb) {
    height: $header-height-tb;
  }

  @include mq(sp) {
    height: $header-height-sp;
  }
}

.content {
  width: 1000px;
  min-height: calc(100% - #{$header-height-pc});
  margin: 0 auto;
  padding: 50px 0 100px;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;

  @include mq(tb) {
    width: calc(100% - 40px);
    min-height: calc(100% - #{$header-height-tb});
  }

  @include mq(sp) {
    width: calc(100% - 20px);
    min-height: calc(100% - #{$header-height-sp});
  }
}
</style>
