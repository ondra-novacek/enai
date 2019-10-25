<script defer>
    window.onload = randomAvatarDistribution;

    function randomAvatarDistribution(){
        let avatars = ['img/management_avatar.png', 'img/teacher_avatar.png', 'img/student_avatar.png', 'img/researcher.png'],
            stepsToDo = avatars.length,
            temp = '',
            rand;
            
        while (stepsToDo !== 0) {
            rand = Math.floor(Math.random() * avatars.length);
            
            temp = avatars[rand];
            avatars[rand] = avatars[stepsToDo-1];
            avatars[stepsToDo-1] = temp;

            stepsToDo -= 1;
        }
        document.getElementsByClassName("who_picture")[0].src = 'asdsd';
        let imgs = document.getElementsByClassName("who_picture");

        for (let index = 0; index < imgs.length; index++) {
            let img = imgs[index];
            img.src = avatars[index];
        }
    }
  </script>