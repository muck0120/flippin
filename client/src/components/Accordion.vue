<template>
  <div v-cloak :class="$style.accordion">
    <div
      :class="[$style.trigger, { [$style.open]: isOpen }]"
      @click="accordionToggle()"
    >
      <span v-if="!isOpen">回答を開く</span>
      <span v-if="isOpen">回答を閉じる</span>
    </div>
    <transition
      name="accordion"
      @before-enter="beforeEnter"
      @enter="enter"
      @before-leave="beforeLeave"
      @leave="leave"
    >
      <div
        v-show="isOpen"
        :class="[$style.target, { [$style.open]: isOpen }]"
      >
        <div :class="$style.body">
          <slot />
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  data () {
    return {
      isOpen: false
    }
  },
  methods: {
    accordionToggle () {
      this.isOpen = !this.isOpen
    },
    beforeEnter (el) {
      el.style.height = '0'
    },
    enter (el) {
      el.style.height = el.scrollHeight + 'px'
    },
    beforeLeave (el) {
      el.style.height = el.scrollHeight + 'px'
    },
    leave (el) {
      el.style.height = '0'
    }
  }
}
</script>

<style lang="scss" scoped>
.accordion-enter-active {
  animation-duration: 0.3s;
  animation-fill-mode: both;
  animation-name: accordion-anime-open;
}

.accordion-leave-active {
  animation-duration: 0.3s;
  animation-fill-mode: both;
  animation-name: accordion-anime-close;
}

@keyframes accordion-anime-open {
  0% {
    opacity: 0;
  }

  100% {
    opacity: 1;
  }
}

@keyframes accordion-anime-close {
  0% {
    opacity: 1;
  }

  100% {
    opacity: 0;
  }
}
</style>

<style lang="scss" module>
.accordion {
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: 10px;
}

.trigger {
  position: relative;
  display: block;
  width: 100%;
  transition: all 0.2s ease-in;
  color: #fff;
  font-size: 24px;
  font-weight: bold;
  padding: 30px;
  cursor: pointer;
  background-color: #00b8a9;

  @include mq(tb) {
    font-size: 21px;
    padding: 25px;
  }

  @include mq(sp) {
    font-size: 18px;
    padding: 20px;
  }
}

.trigger::after {
  $square-pc: 30px;
  $square-tb: 25px;
  $square-sp: 20px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  right: 30px;
  background-image: url('~assets/images/arrow-white.svg');
  background-position: center center;
  background-size: contain;
  background-repeat: no-repeat;
  transform: rotate(90deg);
  transition: all 0.3s;

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

.trigger.open::after {
  transform: rotate(270deg);
  transition: all 0.3s;
}

.target {
  overflow: hidden;
  transition: height 0.4s ease-in-out;
}

.body {
  padding: 30px;

  @include mq(tb) {
    padding: 25px;
  }

  @include mq(sp) {
    padding: 20px;
  }
}
</style>
