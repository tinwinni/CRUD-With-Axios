<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Api CRUD</title>
    <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
        <div class="col-md-8">
            <h3 class="text-info">Posts</h3>

            <span id="successMsg">

            </span>


           <table class="table table-bordered table-hover">
               <thead >
                   <tr>
                       <th>ID</th>
                       <th>Title</th>
                       <th>Description</th>
                       <th>Action</th>
                   </tr>
               </thead>
               <tbody id="tableBody">
                        <!-- <tr>

                            <td>${item.id}</td>
                            <td>${item.title}</td>
                            <td>${item.description}</td>
                            <td>
                                <button class="btn btn-success btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Delete</button>

                            </td>

                        </tr> -->
               </tbody>
           </table>
        </div>
        <div class="col-md-4">
            <h4 class="text-info">Create Posts</h4>
            <span id="success"></span>
        <form name="myForm">
            <div class="form-group">
                <label for="">Title</label>
                <input type="text" class="form-control mt-2" name="title" >
                <span id="titleError"></span>
            </div>

            <div class="form-group">
                <label for="">Description</label>
              <textarea name="description" class="form-control mt-2" id="" cols="30" rows="4"></textarea>
              <span id="desError"></span>
            </div>

            <button type="submit" class="btn btn-primary mt-3 form-control">Submit</button>
        </form>
        </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form name="editForm">
      <div class="modal-body">
      <div class="form-group">
                <label for="">Title</label>
                <input type="text" class="form-control mt-2" name="title" >
                <span id="titleError"></span>
            </div>

            <div class="form-group">
                <label for="">Description</label>
              <textarea name="description" class="form-control mt-2" id="" cols="30" rows="4"></textarea>
              <span id="desError"></span>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
      </form>
      
    </div>
  </div>
</div>


    <!-- JavaScript Bundle with Popper -->
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <!-- Axios link -->
         <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            // Read
            let tableBody = document.querySelector('#tableBody');
            let titleList = document.getElementsByClassName('titleList');
            let descList = document.getElementsByClassName('descList');
            let idList = document.getElementsByClassName('idList');
            let btnList = document.getElementsByClassName('btnList');


         axios.get('api/posts')


              .then(response => {
                  response.data.forEach(item => {
                    displayFunction(item);
                  });
              })
              .catch(error => console.log(error));
       
       
       
        //Create

        let myform = document.forms['myForm'];
        let titleInput = myform['title'];
        let descriptionInput = myform['description'];

        myform.onsubmit = function(e){
            e.preventDefault();
            axios.post('/api/posts',{
                title : titleInput.value,
                description : descriptionInput.value
            })
            .then(response => {
                let titleError = document.querySelector('#titleError');
                    let descError = document.querySelector('#desError');
                console.log(response);
                if(response.data.msg == 'Data Created Successfully'){
                    let success = document.querySelector('#success');
                alertMsg(response.data.msg) ;
                

 
 
                myform.reset();

              displayFunction(response.data.post);
              titleError.innerHTML = descError.innerHTML ='';

                }else{
                   
                    if(titleInput.value == ''){
                        titleError.innerHTML =`<i class="text-danger">${response.data.msg.title} </i>`
                    }else{
                        titleError.innerHTML ='';
                    }
                    
                   if(descriptionInput.value == ''){
                    descError.innerHTML = `<i class="text-danger">${response.data.msg.description} </i> `

                   }
                   else{
                        titleError.innerHTML ='';
                    }
                    
                }
                
                
            })
            .catch(err => console.log(err.response));

            console.log(titleInput.value);
        }
        //Edit
        let editForm = document.forms['editForm'];
        let editTitleInput = editForm['title'];
        let editDesInput = editForm['description'];
        let updatePostId;
        let oldTitle;
        function editBtn(postId){
            updatePostId = postId;
            axios.get('api/posts/'+postId)
            .then(response => {
                editTitleInput.value = response.data.title;
                editDesInput.value = response.data.description;
                
                oldTitle =response.data.title;
            })
            .catch(err => {
                console.log(err);
            })
        }
        //Update
        editForm.onsubmit = function(e){
            e.preventDefault();
            
            
            axios.put('api/posts/'+updatePostId,{
                title : editTitleInput.value,
                description : editDesInput.value
            })
                .then(response => {
                  alertMsg(response.data.msg);
                 
                
                  for(let i=0;i<titleList.length;i++){
                    if(titleList[i].innerHTML == oldTitle){
                        titleList[i].innerHTML = editTitleInput.value;
                        descList[i].innerHTML = editDesInput.value

                    }
                }
               
               
                })
                .catch(err => {
                    console.log(err);
                });
        }
        //Delete

        function deleteBtn(postId){
            if(confirm('Sure to delete?')){
                axios.delete('api/posts/'+postId)
            .then(response => {
                console.log(response.data);
               alertMsg(response.data.msg);

               for(let i=0; i<titleList.length;i++){
                   if(titleList[i].innerHTML == response.data.deletePost.title){
                       titleList[i].style.display ='none';
                       idList[i].style.display ='none';
                       descList[i].style.display = 'none';
                       btnList[i].style.display = 'none';
                   }
               }

            })
            .catch(err => {
                console.log(err);
            })
            }
        }

        //Helper Function
        function displayFunction(data){
            tableBody.innerHTML +=`
                        <tr>
                            <td class='idList'>${data.id}</td>
                            <td class="titleList">${data.title}</td>
                            <td class="descList">${data.description}</td>
                            <td class='btnList'>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"" onclick="editBtn(${data.id})">Edit</button>

             
                                <button class="btn btn-danger btn-sm" onclick="deleteBtn(${data.id})">Delete</button>

                            </td>

                        </tr>`;
        }
        function alertMsg(message){
            document.querySelector('#successMsg').innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>${message}</strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                
                `
        }
       </script>
</body>
</html>