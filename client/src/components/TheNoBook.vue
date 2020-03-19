<template>
  <div :class="$style.wrap">
    <p :class="$style.heading">
      Sorry!!
    </p>
    <p :class="$style.text">
      条件に合致する問題集がありません。<br>
      条件を変更するか、新たに問題集を<br :class="$style.sp">作成してください。
    </p>
    <NLink to="/books/create" :class="$style.button">
      新規問題集作成
      <fa :icon="faPlus" :class="$style.plus" />
    </NLink>
  </div>
</template>

<script>
import { faPlus } from '@fortawesome/free-solid-svg-icons'

export default {
  computed: {
    faPlus () {
      return faPlus
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
}

.heading {
  font-size: 40px;
  font-weight: bold;
  text-align: center;
  line-height: 1.5;

  @include mq(tb) {
    font-size: 33px;
  }

  @include mq(sp) {
    font-size: 26px;
  }
}

.text {
  margin-top: 20px;
  font-size: 16px;
  font-weight: bold;
  text-align: center;
  line-height: 1.5;

  @include mq(tb) {
    font-size: 15px;
  }

  @include mq(sp) {
    font-size: 14px;
  }
}

.sp {
  display: none;

  @include mq(sp) {
    display: block;
  }
}

.button {
  $height-pc: 60px;
  $height-tb: 55px;
  $height-sp: 50px;

  display: flex;
  justify-content: center;
  align-items: center;
  width: 260px;
  height: $height-pc;
  border-radius: $height-pc / 2;
  margin: 50px auto 0;
  background-color: #f6416c;
  color: #fff;
  font-size: 20px;
  font-weight: bold;
  white-space: nowrap;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  position: relative;
  cursor: pointer;
  transition: all 0.3s;

  @include mq(tb) {
    width: 250px;
    height: $height-tb;
    border-radius: $height-tb / 2;
    margin: 40px auto 0;
    font-size: 18px;
  }

  @include mq(sp) {
    width: 240px;
    height: $height-sp;
    border-radius: $height-sp / 2;
    margin: 30px auto 0;
    font-size: 16px;
  }
}

.button:hover {
  box-shadow: none;
  transition: all 0.3s;
}

.plus {
  position: absolute;
  right: 20px;
  transition: all 0.3s;
}

.button:hover .plus {
  transform: rotate(90deg);
  transition: all 0.3s;
}
</style>
