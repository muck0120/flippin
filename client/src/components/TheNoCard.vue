<template>
  <div>
    <p :class="$style.text">
      問題を追加して自分だけの問題集を作成しよう！
    </p>
    <NLink :to="`/books/${1}/cards/create`" :class="$style.button">
      問題を作成する
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
.text {
  font-size: 24px;
  font-weight: bold;
  text-align: center;

  @include mq(tb) {
    font-size: 19px;
  }

  @include mq(sp) {
    font-size: 14px;
  }
}

.button {
  $height-pc: 50px;
  $height-tb: 45px;
  $height-sp: 40px;

  display: flex;
  justify-content: center;
  align-items: center;
  width: 250px;
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
    width: 225px;
    height: $height-tb;
    border-radius: $height-tb / 2;
    margin: 40px auto 0;
    font-size: 17px;
  }

  @include mq(sp) {
    width: 200px;
    height: $height-sp;
    border-radius: $height-sp / 2;
    margin: 30px auto 0;
    font-size: 14px;
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
