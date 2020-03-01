<template>
  <div :class="$style.wrap">
    <h2 :class="$style.title">
      ごめんなさい、<br :class="$style.sp">ページを表示できません！
    </h2>
    <p :class="$style.text">
      既にページが消去されているか、<br :class="$style.sp">何らかの不具合でページが表示できません。<br>
      また、Internet Exploreでは<br :class="$style.sp">本サイトはご利用頂けません。
    </p>
    <p :class="$style.text">
      しばらく時間を空けて、再度アクセスするか、<br>
      以下のボタンでトップページに戻って<br :class="$style.sp">操作をやり直してください。
    </p>
    <NLink to="/" :class="$style.button">
      トップへ戻る
    </NLink>
  </div>
</template>

<style lang="scss" module>
.wrap {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.title {
  font-size: 40px;
  font-weight: bold;
  text-align: center;
  line-height: 1.5;
  white-space: nowrap;

  @include mq(tb) {
    font-size: 30px;
  }

  @include mq(sp) {
    font-size: 24px;
  }
}

.sp {
  display: none;

  @include mq(sp) {
    display: block;
  }
}

.text {
  margin-top: 20px;
  font-size: 16px;
  font-weight: bold;
  text-align: center;
  line-height: 1.5;
  white-space: nowrap;

  @include mq(tb) {
    font-size: 14px;
  }

  @include mq(sp) {
    font-size: 12px;
  }
}

.button {
  $height-pc: 60px;
  $height-tb: 55px;
  $height-sp: 50px;

  width: 250px;
  height: $height-pc;
  background-color: #f6416c;
  border-radius: $height-pc / 2;
  color: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 20px;
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
    font-size: 18px;
    border-radius: $height-tb / 2;
  }

  @include mq(sp) {
    width: 250px;
    height: $height-sp;
    margin-top: 30px;
    font-size: 16px;
    border-radius: $height-sp / 2;
  }
}

.button:hover {
  box-shadow: none;
  transition: all 0.3s;
}
</style>
