<template>
  <div :class="$style.wrap">
    <button
      @click="$emit('proceed')"
      :class="$style.button"
    >
      閉じる
    </button>
  </div>
</template>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;

  @include mq(sp) {
    margin-top: 30px;
  }
}

.button {
  $height-pc: 40px;
  $height-sp: 35px;

  width: 200px;
  height: $height-pc;
  background-color: #f6416c;
  border: 3px solid #f6416c;
  border-radius: $height-pc / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #fff;
  font-size: 18px;
  font-weight: bold;
  transition: all 0.3s;
  white-space: nowrap;

  @include mq(sp) {
    width: 80%;
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 14px;
  }
}

.button:hover {
  background-color: #fff;
  color: #f6416c;
  transition: all 0.3s;
}
</style>
