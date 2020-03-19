<template>
  <transition
    name="modal"
    appear
  >
    <div
      :class="$style.wrap"
      @click.self="$emit('close')"
    >
      <div :class="$style.window">
        <h3 :class="$style.heading">
          <slot name="title"/>
        </h3>
        <p :class="$style.desc">
          <slot name="desc"/>
        </p>
        <div :class="$style.buttons">
          <slot name="button"/>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  mounted () {
    document.body.classList.add('prevent-scroll')
  },
  destroyed () {
    document.body.classList.remove('prevent-scroll')
  }
}
</script>

<style lang="scss" scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.4s;
}

.modal-enter,
.modal-leave-to {
  opacity: 0;
}
</style>

<style lang="scss" module>
.wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(#fff, 0.6);
  z-index: 9999;
}

.window {
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: 10px;
  padding: 20px 20px 40px;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);

  @include mq(sp) {
    padding: 15px 15px 30px;
  }
}

.heading {
  font-size: 24px;
  font-weight: bold;
  text-align: center;
  line-height: 1.5;

  @include mq(sp) {
    font-size: 18px;
  }
}

.desc {
  margin-top: 10px;
  font-size: 16px;
  text-align: center;
  line-height: 1.5;

  @include mq(sp) {
    font-size: 12px;
  }
}

.buttons {
  margin-top: 30px;
}
</style>
