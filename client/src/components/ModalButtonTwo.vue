<template>
  <div :class="$style.wrap">
    <button
      @click="$emit('proceed')"
      :class="[$style.button, $style.red]"
    >
      <slot name="proceedLavel"/>
    </button>
    <button
      @click="$emit('cancel')"
      :class="$style.button"
    >
      <slot name="cancelLavel"/>
    </button>
  </div>
</template>

<style lang="scss" module>
.wrap {
  margin-top: 30px;
  width: 100%;
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

  width: 170px;
  height: $height-pc;
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: $height-pc / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #00b8a9;
  font-size: 18px;
  font-weight: bold;
  transition: all 0.3s;
  white-space: nowrap;

  @include mq(sp) {
    width: 120px;
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 14px;
  }
}

.button:hover {
  opacity: 0.7;
  transition: all 0.3s;
}

.button.red {
  background-color: #f6416c;
  border: 3px solid #f6416c;
  color: #fff;
}

.button + .button {
  margin-left: 20px;

  @include mq(sp) {
    margin-left: 10px;
  }
}
</style>
