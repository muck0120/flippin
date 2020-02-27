<template>
  <div :class="$style.wrap">
    <h2 :class="$style.desc">
      <span :class="$style.logo">Flippin</span>で自作問題集を<br :class="$style.sp">みんなと共有しよう！
    </h2>
    <NLink to="/books" :class="$style.button">
      スタート
    </NLink>
  </div>
</template>

<script>
export default {
  layout: 'top'
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.desc {
  color: #fff;
  font-size: 36px;
  text-align: center;

  @include mq(tb) {
    font-size: 30px;
  }

  @include mq(sp) {
    font-size: 24px;
    line-height: 1.2;
  }
}

.logo {
  font-family: 'Baloo Bhai', cursive;
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

  width: 350px;
  height: $height-pc;
  background-color: #f6416c;
  border-radius: $height-pc / 2;
  color: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 30px;
  margin-top: 50px;
  font-weight: bold;
  white-space: nowrap;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  position: relative;
  transition: all 0.3s;

  @include mq(tb) {
    width: 300px;
    height: $height-tb;
    margin-top: 40px;
    font-size: 26px;
    border-radius: $height-tb / 2;
  }

  @include mq(sp) {
    width: 250px;
    height: $height-sp;
    margin-top: 30px;
    font-size: 22px;
    border-radius: $height-sp / 2;
  }
}

.button::after {
  $square-pc: 25px;
  $square-tb: 20px;
  $square-sp: 20px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  right: 30px;
  background-image: url('~assets/images/arrow-white.svg');
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center right;
  transition: all 0.3s;

  @include mq(tb) {
    width: $square-tb;
    height: $square-tb;
    top: calc(50% - #{$square-tb} / 2);
    right: 25px;
  }

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    right: 25px;
  }
}

.button:hover {
  box-shadow: none;
  transition: all 0.3s;
}

.button:hover::after {
  right: 25px;
  transition: all 0.3s;

  @include mq(tb) {
    right: 20px;
  }
}
</style>
