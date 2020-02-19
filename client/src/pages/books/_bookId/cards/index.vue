<template>
  <div :class="$style.wrap">
    <BookHeading @open="modal = true" />
    <div :class="$style.cards">
      <!-- 問題がある場合 -->
      <template v-if="true">
        <div
          v-for="n in 10"
          :key="n"
          :class="$style.card"
        >
          <div v-if="true" :class="$style.sort">
            <fa :icon="faSort" />
          </div>
          <Card />
        </div>
      </template>
      <!-- 問題がない場合 -->
      <template v-if="false">
        <NoCard />
      </template>
    </div>
    <div v-if="false" :class="$style.buttons">
      <button :class="[$style.button, $style.red]">
        問題を解く
        <img
          src="@/assets/images/arrow-white.svg"
          alt=""
          :class="$style.icon"
        >
      </button>
      <button :class="$style.button">
        問題を追加する
        <fa :icon="faPlus" :class="$style.icon" />
      </button>
    </div>
    <Modal v-if="modal" @close="modal = false" />
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import { faPlus, faSort } from '@fortawesome/free-solid-svg-icons'

import BookHeading from '@/components/TheHeadingBook.vue'
import NoCard from '@/components/TheNoCard.vue'
import Modal from '@/components/TheModal.vue'
import Card from '@/components/ItemCard.vue'

export default Vue.extend({
  components: {
    BookHeading,
    NoCard,
    Modal,
    Card
  },
  data () {
    return {
      modal: false
    }
  },
  computed: {
    faPlus () {
      return faPlus
    },
    faSort () {
      return faSort
    }
  }
})
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
}

.cards {
  margin-top: 30px;
}

.card {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card + .card {
  margin-top: 10px;
}

.sort {
  font-size: 30px;
  padding-right: 10px;

  @include mq(tb) {
    font-size: 27px;
    padding-right: 10px;
  }

  @include mq(sp) {
    font-size: 24px;
    padding-right: 5px;
  }
}

.buttons {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  bottom: 30px;
  left: 0;
  pointer-events: none;

  @include mq(tb) {
    bottom: 20px;
  }

  @include mq(sp) {
    bottom: 20px;
  }
}

.button {
  $height-pc: 50px;
  $height-tb: 45px;
  $height-sp: 40px;

  width: 250px;
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
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  pointer-events: auto;
  position: relative;

  @include mq(tb) {
    width: 200px;
    height: $height-tb;
    border-radius: $height-tb / 2;
    font-size: 16px;
  }

  @include mq(sp) {
    width: 150px;
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 13px;
  }
}

.button.red {
  background-color: #f6416c;
  border: 1px solid #f6416c;
  color: #fff;
}

.button + .button {
  margin-left: 20px;

  @include mq(sp) {
    margin-left: 10px;
  }
}

.icon {
  $square-pc: 20px;
  $square-tb: 16px;
  $square-sp: 12px;

  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  right: 15px;
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
    right: 10px;
  }
}

.button:hover .icon {
  transform: rotate(90deg);
  transition: all 0.3s;
}

.button.red:hover .icon {
  transform: translateX(5px);
}
</style>
