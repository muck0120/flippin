<template>
  <div>
    <header :class="$style.header">
      <HeaderApp />
    </header>
    <section :class="[$style.content, $style.green]">
      <div :class="$style.content__inner">
        <nuxt />
      </div>
    </section>
    <footer :class="$style.footer">
      <FooterApp />
    </footer>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import HeaderApp from '@/components/TheHeaderApp.vue'
import FooterApp from '@/components/TheFooterApp.vue'

export default Vue.extend({
  components: {
    HeaderApp,
    FooterApp
  }
})
</script>

<style lang="scss" module>
$header-height-pc: 70px;
$header-height-tb: 60px;
$header-height-sp: 50px;
$footer-height-pc: 70px;
$footer-height-tb: 60px;
$footer-height-sp: 50px;

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
  width: 100%;
  min-height: calc(100% - #{$header-height-pc} - #{$footer-height-pc});
  display: flex;
  justify-content: flex-start;
  align-items: stretch;

  @include mq(tb) {
    min-height: calc(100% - #{$header-height-tb} - #{$footer-height-tb});
  }

  @include mq(sp) {
    min-height: calc(100% - #{$header-height-sp} - #{$footer-height-sp});
  }
}

.content.green {
  background-color: #00b8a9;
}

.content__inner {
  width: 1000px;
  margin: 0 auto;
  padding: 50px 0 100px;

  @include mq(tb) {
    width: calc(100% - 40px);
    padding: 50px 0 100px;
  }

  @include mq(sp) {
    width: calc(100% - 20px);
    padding: 50px 0 100px;
  }
}

.footer {
  width: 100%;
  height: $footer-height-pc;

  @include mq(tb) {
    height: $footer-height-tb;
  }

  @include mq(sp) {
    height: $footer-height-sp;
  }
}
</style>
