<template>
  <div :class="$style.wrap">
    <NLink
      v-if="true"
      to=""
      :class="[$style.button, $style.prev]"
    >
      前の問題
    </NLink>
    <NLink
      v-if="true"
      to=""
      :class="[$style.button, $style.done]"
    >
      テストを完了する
    </NLink>
    <NLink
      v-if="true"
      to=""
      :class="[$style.button, $style.next]"
    >
      次の問題
    </NLink>
  </div>
</template>

<style lang="scss" module>
$button-width-pc: 250px;
$button-width-tb: 200px;
$button-width-sp: 140px;
$button-height-pc: 50px;
$button-height-tb: 45px;
$button-height-sp: 40px;

.wrap {
  width: 100%;
  position: relative;

  @include mq(sp) {
    height: calc(#{$button-height-sp} * 2 + 20px);
  }
}

.button {
  display: flex;
  justify-content: center;
  align-items: center;
  width: $button-width-pc;
  height: $button-height-pc;
  border-radius: $button-height-pc / 2;
  border: 3px solid #00b8a9;
  background-color: #fff;
  color: #00b8a9;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  white-space: nowrap;
  transition: all 0.3s;

  @include mq(tb) {
    width: $button-width-tb;
    height: $button-height-tb;
    border-radius: $button-height-tb / 2;
    font-size: 16px;
  }

  @include mq(sp) {
    width: $button-width-sp;
    height: $button-height-sp;
    border-radius: $button-height-sp / 2;
    font-size: 14px;
  }
}

.button.prev {
  position: absolute;
  top: 0;
  left: 0;
}

.button.prev::before {
  $square-pc: 15px;
  $square-sp: 12px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  left: 25px;
  background-image: url('~assets/images/arrow-green.svg');
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center center;
  transform: rotate(180deg);
  transition: all 0.3s;

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    left: 10px;
  }
}

.button.prev:hover::before {
  transition: all 0.3s;
  transform: rotate(180deg) translateX(5px);
}

.button.next {
  position: absolute;
  top: 0;
  right: 0;
}

.button.next::before {
  $square-pc: 15px;
  $square-sp: 12px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  right: 25px;
  background-image: url('~assets/images/arrow-green.svg');
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center center;

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    right: 10px;
  }
}

.button.next:hover::before {
  transition: all 0.3s;
  transform: translateX(5px);
}

.button.done {
  color: #fff;
  border: none;
  background-color: #f6416c;
  position: absolute;
  top: 0;
  left: calc(50% - #{$button-width-pc} / 2);

  @include mq(tb) {
    left: calc(50% - #{$button-width-tb} / 2);
  }

  @include mq(sp) {
    width: 100%;
    top: auto;
    left: 0;
    bottom: 0;
  }
}

.button.done:hover {
  transition: all 0.3s;
  border: 3px solid #f6416c;
  background-color: #fff;
  color: #f6416c;
}
</style>
