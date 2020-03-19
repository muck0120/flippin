<template>
  <div
    v-if="isLoading"
    :class="$style.wrap"
  >
    <div :class="$style.spinner">
      <div :class="$style.rect1"></div>
      <div :class="$style.rect2"></div>
      <div :class="$style.rect3"></div>
      <div :class="$style.rect4"></div>
      <div :class="$style.rect5"></div>
    </div>
    <div :class="$style.text">
      Loading...
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    isLoading: false
  }),
  methods: {
    start () {
      document.body.classList.add('prevent-scroll')
      this.isLoading = true
    },
    finish () {
      document.body.classList.remove('prevent-scroll')
      this.isLoading = false
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  z-index: 9999;
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  width: 100%;
  height: 100%;
  background-color: rgba(#fff, 0.7);
}

.spinner {
  margin: 0 auto;
  width: 50px;
  height: 40px;
  text-align: center;
  font-size: 10px;
}

.spinner > div {
  background-color: #00b8a9;
  height: 100%;
  width: 6px;
  display: inline-block;
  -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
  animation: sk-stretchdelay 1.2s infinite ease-in-out;
}

.spinner .rect2 {
  -webkit-animation-delay: -1.1s;
  animation-delay: -1.1s;
}

.spinner .rect3 {
  -webkit-animation-delay: -1s;
  animation-delay: -1s;
}

.spinner .rect4 {
  -webkit-animation-delay: -0.9s;
  animation-delay: -0.9s;
}

.spinner .rect5 {
  -webkit-animation-delay: -0.8s;
  animation-delay: -0.8s;
}

@-webkit-keyframes sk-stretchdelay {
  0%,
  40%,
  100% {
    -webkit-transform: scaleY(0.4);
  }

  20% {
    -webkit-transform: scaleY(1);
  }
}

@keyframes sk-stretchdelay {
  0%,
  40%,
  100% {
    transform: scaleY(0.4);
    -webkit-transform: scaleY(0.4);
  }

  20% {
    transform: scaleY(1);
    -webkit-transform: scaleY(1);
  }
}

.text {
  margin-top: 15px;
  font-weight: bold;
  font-size: 20px;
}
</style>
