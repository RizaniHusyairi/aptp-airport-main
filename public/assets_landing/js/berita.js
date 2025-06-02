var swiperEl = document.querySelector(".newsFirstSwiper");
    Object.assign(swiperEl, {
      grabCursor: true,
      effect: "creative",
      creativeEffect: {
        prev: {
          shadow: true,
          translate: [0, 0, -400],
        },
        next: {
          translate: ["100%", 0, 0],
        },
      },
      injectStyles: [`
      .swiper-pagination-bullet {
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        font-size: 12px;
        color: #000;
        opacity: 1;
        background: rgba(0, 0, 0, 0.2);
      }

      .swiper-pagination-bullet-active {
        color: #fff;
        background: #d89d4e;
      }
      `],
      pagination: {
        clickable: true,
        renderBullet: function (index, className) {
          return '<span class="' + className + '"></span>';
        },
      },
    });
    swiperEl.initialize()
