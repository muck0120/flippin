<template>
  <div>
    <section :class="$style.wrap">
      <h2 :class="$style.title">
        {{ book.book_title }}
      </h2>
      <p
        v-if="book.book_desc"
        :class="$style.desc"
      >
        {{ book.book_desc }}
      </p>
      <div
        v-if="isFooter"
        :class="$style.footer"
      >
        <div
          @click.prevent="clickFavorite(book.book_id, book.book_is_favorite)"
          :class="[$style.favorite, { [$style.active]: book.book_is_favorite }]"
        >
          {{ book.book_favorite_count }}いいね
        </div>
        <template v-if="!user || book.user_id !== user.user_id">
          <cite :class="$style.author">
            Created by
            <span :class="$style.name">
              {{ book.book_username_created_by }}
            </span>
          </cite>
        </template>
        <template v-if="user && book.user_id === user.user_id">
          <div :class="$style.private">
            <div :class="$style.publish">
              <span v-if="book.book_is_publish">公開</span>
              <span v-if="!book.book_is_publish">非公開</span>
            </div>
            <NLink
              :to="`/books/${book.book_id}/update`"
              :class="$style.operate"
            >
              <fa
                :icon="faEdit"
                :class="$style.icon"
              />
            </NLink>
            <div
              :class="$style.operate"
              @click="$emit('confirmDelete')"
            >
              <fa
                :icon="faTrash"
                :class="$style.icon"
              />
            </div>
          </div>
        </template>
      </div>
    </section>
    <NLink
      v-if="backTo"
      :to="backTo"
      :class="$style.back"
    >
      一覧へ戻る
    </NLink>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { faEdit, faTrash } from '@fortawesome/free-solid-svg-icons'

export default {
  props: {
    isFooter: {
      type: Boolean,
      required: true
    },
    backTo: {
      type: String,
      default: '',
      required: false
    }
  },
  computed: {
    faEdit () {
      return faEdit
    },
    faTrash () {
      return faTrash
    },
    ...mapState('user', ['user']),
    ...mapState('book', ['book'])
  },
  methods: {
    async clickFavorite (bookId, isFavorite) {
      if (!this.user) {
        this.$emit('requiredLogin')
        return false
      }
      isFavorite ?
        await this.$store.dispatch('book/deleteFavorite', { bookId }) :
        await this.$store.dispatch('book/storeFavorite', { bookId })
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: 10px;
  padding: 30px;

  @include mq(tb) {
    padding: 25px;
  }

  @include mq(sp) {
    padding: 20px;
  }
}

.title {
  font-size: 20px;
  font-weight: bold;
  line-height: 1.2;

  @include mq(tb) {
    font-size: 17px;
  }

  @include mq(sp) {
    font-size: 14px;
  }
}

.desc {
  margin-top: 20px;
  font-size: 16px;
  line-height: 1.2;

  @include mq(tb) {
    margin-top: 15px;
    font-size: 14px;
  }

  @include mq(sp) {
    margin-top: 10px;
    font-size: 12px;
  }
}

.footer {
  margin-top: 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;

  @include mq(tb) {
    margin-top: 25px;
  }

  @include mq(sp) {
    margin-top: 20px;
  }
}

.favorite {
  padding-left: 25px;
  font-size: 16px;
  font-weight: bold;
  white-space: nowrap;
  color: #999;
  position: relative;
  transition: all 0.3s;
  cursor: pointer;

  @include mq(tb) {
    padding-left: 23px;
    font-size: 14px;
  }

  @include mq(sp) {
    padding-left: 18px;
    font-size: 12px;
  }
}

.favorite::before {
  $square-pc: 20px;
  $square-tb: 18px;
  $square-sp: 16px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  left: 0;
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

.author {
  font-size: 16px;
  font-style: normal;

  @include mq(tb) {
    font-size: 14px;
  }

  @include mq(sp) {
    font-size: 12px;
  }
}

.name {
  font-weight: bold;
}

.private {
  display: flex;
  justify-content: flex-start;
  align-items: center;
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

.operate {
  display: block;
  margin-left: 15px;
}

.icon {
  font-size: 26px;
  color: #999;
  cursor: pointer;
  transition: all 0.3s;

  @include mq(tb) {
    font-size: 24px;
  }

  @include mq(sp) {
    font-size: 22px;
  }
}

.icon:hover {
  color: #00b8a9;
  transition: all 0.3s;
}

.back {
  display: inline-block;
  margin-top: 15px;
  padding-left: 20px;
  cursor: pointer;
  position: relative;
}

.back::before {
  $square: 15px;

  content: '';
  display: block;
  width: $square;
  height: $square;
  position: absolute;
  top: calc(50% - #{$square} / 2);
  left: 0;
  background-image: url('~assets/images/arrow-green.svg');
  background-size: contain;
  background-position: center center;
  background-repeat: no-repeat;
  transform: rotate(180deg);
  transition: all 0.3s;
}

.back:hover::before {
  left: -5px;
  transition: all 0.3s;
}
</style>
