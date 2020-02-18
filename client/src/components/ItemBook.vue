<template>
  <NLink to="/books/1/cards" :class="$style.wrap">
    <div :class="$style.header">
      <h2 :class="$style.title">
        問題集タイトル問題集タイトル問題集タイトル問題集タイトル問題集タイトル問題集タイトル
      </h2>
      <p :class="$style.desc">
        問題集補足、問題集補足、問題集補足、問題集補足、問題集補足、問題集補足、問題集補足、問題集補足、
      </p>
    </div>
    <div :class="$style.footer">
      <!-- みんなの問題集 or お気に入り -->
      <template v-if="true">
        <cite :class="$style.author">
          Created by <span :class="$style.name">TestUser</span>
        </cite>
      </template>
      <!-- MY問題集 -->
      <template v-if="false">
        <div :class="$style.publish">
          <span v-if="false">公開</span>
          <span v-if="true">非公開</span>
        </div>
      </template>
      <div :class="[$style.favorite, { [$style.active]: false }]">
        130いいね
      </div>
    </div>
  </NLink>
</template>

<style lang="scss" module>
.wrap {
  width: 100%;
  display: block;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  cursor: pointer;
  transition: all 0.3s;
}

.wrap:hover {
  box-shadow: none;
  transition: all 0.3s;
}

.header {
  background-color: #00b8a9;
  padding: 15px;
  color: #fff;
}

.title {
  font-size: 16px;
  font-weight: bold;
  line-height: 1.3;
  display: -webkit-box;
  overflow: hidden;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;

  @include mq(tb) {
    font-size: 15px;
  }

  @include mq(sp) {
    font-size: 14px;
  }
}

.desc {
  margin-top: 15px;
  font-size: 14px;
  line-height: 1.3;
  display: -webkit-box;
  overflow: hidden;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;

  @include mq(tb) {
    font-size: 13px;
  }

  @include mq(sp) {
    font-size: 12px;
  }
}

.footer {
  font-size: 14px;
  background-color: #fff;
  padding: 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;

  @include mq(tb) {
    font-size: 13px;
  }

  @include mq(sp) {
    font-size: 12px;
  }
}

.author {
  font-style: normal;
}

.name {
  font-weight: bold;
}

.publish {
  $height-pc: 30px;
  $height-tb: 30px;
  $height-sp: 25px;

  width: 90px;
  height: $height-pc;
  border-radius: $height-pc / 2;
  background-color: #ffde7d;
  color: #999;
  font-size: 16px;
  font-weight: bold;
  display: flex;
  justify-content: center;
  align-items: center;

  @include mq(tb) {
    font-size: 14px;
    width: 85px;
    height: $height-tb;
    border-radius: $height-tb / 2;
  }

  @include mq(sp) {
    font-size: 12px;
    width: 80px;
    height: $height-sp;
    border-radius: $height-sp / 2;
  }
}

.favorite {
  font-weight: bold;
  white-space: nowrap;
  color: #999;
  position: relative;
  transition: all 0.3s;
}

.favorite::before {
  $square-pc: 18px;
  $square-tb: 16px;
  $square-sp: 14px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  left: -20px;
  background-image: url('~assets/images/heart-empty-gray.svg');
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center center;
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

.favorite:hover {
  color: #f6416c;
  transition: all 0.3s;
}

.favorite:hover::before {
  background-image: url('~assets/images/heart-empty-red.svg');
}

.favorite.active {
  color: #f6416c;
}

.favorite.active::before {
  background-image: url('~assets/images/heart-active.svg');
}
</style>
