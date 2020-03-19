<template>
  <div :class="$style.wrap">
    <div :class="$style.inner">
      <h1 :class="$style.title">
        Flippin
      </h1>
      <div :class="$style.box">
        <nuxt />
      </div>
    </div>
  </div>
</template>

<style lang="scss" module>
.wrap {
  width: 100%;
  min-width: 100%;
  padding: 50px 0 100px;
  background-color: #00b8a9;
  display: flex;
  justify-content: center;
  align-items: center;
}

.inner {
  width: 360px;
  margin: 0 auto;

  @include mq(sp) {
    width: calc(100% - 60px);
  }
}

.title {
  font-family: 'Baloo Bhai', cursive;
  color: #fff;
  text-align: center;
  font-size: 42px;

  @include mq(sp) {
    font-size: 36px;
  }
}

.box {
  width: 100%;
  border-radius: 5px;
  padding: 30px;
  background-color: #fff;
  margin-top: 30px;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);

  @include mq(sp) {
    margin-top: 20px;
    padding: 30px 20px;
  }
}
</style>
