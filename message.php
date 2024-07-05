<?php
  if(isset($_SESSION['message'])) :
?>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    html {
        font-family: Arial, Helvetica, sans-serif;
        width: 100%;
        height: 100%;
    }
    .alert{
        width: 80%;
    }
    .alert .text {
          display: block;
          padding: 20px;
          margin: 10px;
          border-radius: 3px;
          border: 1px solid rgb(180, 180, 180);
          background-color: rgb(212, 212, 212);
      }
      .alert .close {
          float: right;
          margin: 20px 20px 0px 0px;
          cursor: pointer;
      }
      .alert .text,
      .alert .close {
          color: rgb(88, 88, 88);
      }
      .alert input {
          display: none;
      }
      .alert input:checked~* {
          animation-name: dismiss, hide;
          animation-duration: 300ms;
          animation-iteration-count: 1;
          animation-timing-function: ease;
          animation-fill-mode: forwards;
          animation-delay: 0s, 100ms;
      }


      .alert.warning .text {
          border: 1px solid rgb(251, 238, 213);
          background-color: rgb(252, 248, 227);
      }

      .alert.warning .text,
      .alert.warning .close {
          color: rgb(192, 152, 83);
      }
      @keyframes dismiss {
          0% {
              opacity: 1;
          }
          90%,
          100% {
              opacity: 0;
              font-size: 0.1px;
              transform: scale(0);
          }
      }

      @keyframes hide {
          100% {
              height: 0px;
              width: 0px;
              overflow: hidden;
              margin: 0px;
              padding: 0px;
              border: 0px;
          }
      }
    </style>
    <div class="alert warning">
      <input type="checkbox" id="alert4" />
      <label class="close" title="close" for="alert4">
        <i class="icon-remove">&#x2715;</i>
      </label>
      <p class="text">
        <strong>Hey!</strong> <?= $_SESSION['message']; ?>
      </p>
    </div>

<?php
  unset($_SESSION['message']);
  endif;
?>