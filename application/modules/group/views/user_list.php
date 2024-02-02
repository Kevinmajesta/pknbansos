<style>
  #paging .on, #paging .disabled { cursor:default; }
  #paging .disabled { text-decoration:none; opacity:.5; }
  #alphaFilter
  {
    margin: 0 auto 10px;
    width: 600px;
  }
  #alphaFilter:after
  {
    clear: both;
    content: "";
    display: block;
    height: 0;
  }
  #alphaFilter span
  {
    display: block;
    float: left;
    margin-right: 3px;
  }
  #alphaFilter ul
  {
    float: left;
    margin: 0 3px 0 0;
    padding: 0;
  }
  #alphaFilter li
  {
    float: left;
    list-style-type: none;
    margin-right: 3px;
  }
  #clear
  {
    clear: none;
    float: left;
  }
  #alphaFilter a:hover
  {
    color: #AEAEAE;
  }
  #alphaFilter a.disabled
  {
    color: #AEAEAE;
    cursor: default;
    opacity: 0.5;
    text-decoration: none;
  }
  
  #li a { padding:2px 5px 5px; }
  
  
</style>


<div id="alphaFilter">
				
		  <form class="form-search" id="searchForm">
		    <input id="term" type="text" class="input-medium search-query" placeholder="Search">
			<a data-bind="click: setTerm, disable: filterTerm"><i data-bind="visible: filterTerm() ==''" style="margin-left: -25px;" class="icon-search"></i></a>
			<a data-bind="visible: filterTerm, click: clearTerm" title="Clear search" href="#"><i style="margin-left: -25px;" class="icon-remove"></i></a>
		    <button data-bind="click: setTerm, disable: filterTerm" type="submit" class="btn" style="display: none;" >
		      Search
		    </button>
		  </form>
			 <span>Filter name by:</span>		  
		  <label class="form-inline">
        	<ul data-bind="template: 'letterTemplate'"> </ul>
      		<a id="clear" href="#" title="Clear Filter" data-bind="click: clearLetter, css: { disabled: filterLetter() === '' }">
            Clear filter
        </a>
		  </label>
		  

        </div>
        <script id="letterTemplate" type="text/x-jquery-tmpl">
            {{each(i, val) letters}}
                <li>
                    <a href="#" title="Filter name by ${ val }" data-bind="click: function() { 
                        filterLetter(val) }, 
                        css: { disabled: val === filterLetter() }">
                        ${ val }
                    </a>
                </li>
            {{/each}}
        </script>
		<button class="btn btn-success" data-bind="enable:canAdd, click: add" >Tambah</button> <br /><br />
		
		<table class="table table-striped">
    
      <tr>
        <th>#</th>
        <th>Username</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Group</th>
        <th>SKPD</th>
        <th data-bind="visible:false"></th>
        <th>Edit</th>
        <th>Delete</th>
        <th>Status Akun</th> 
			</tr>
		  <tbody data-bind="template: { name: 'personTemplate', foreach: showCurrentPage }"></tbody>
		
		</table>
		
		<label id="pageSize">Show <input class="input-mini" type="text" data-bind="value: pageSize" /> of <?php echo $count; ?></label>
		
        <div id="people">
            <nav id="paging" class="pagination">
			<ul>
                <li><a id="all" href="#" data-bind="click: function () { pageSize(people().length); currentPage(0); }, css: { disabled: pageSize() === people().length }">Show all</a></li>
				<li><a id="first" title="First Page" href="#"  data-bind="click: function() { currentPage(0); }, css: { disabled: currentPage() === 0 }">First</a></li>
				<li><a id="prev" title="Previous Page" href="#" data-bind="click: navigate, css: { disabled: currentPage() === 0 }">&laquo;</a></li>
				<li>
				<a style="height:20px;margin-left: -13px; margin-right: -12px;border:none;"><ul style="margin-top: -5px;" data-bind="template: 'pagingTemplate'"></ul></a>
				</li>
					
                <li><a id="next" title="Next Page" href="#" data-bind="click: navigate, css: { disabled: currentPage() === totalPages() - 1 }">&raquo;</a></li>
                <li><a id="last" title="Last Page" href="#" data-bind="click: function() { currentPage(totalPages() - 1); }, css: { disabled: currentPage() === totalPages() - 1 }">Last</a></li>
			</ul>	
            </nav>
            
            <script id="pagingTemplate" type="text/html">
                {{each(i) ko.utils.range(1, totalPages)}} 
                    <li data-bind="click: function() { 
                        currentPage(i) }, 
                        css: {active: i === currentPage() }">
                      <a href="#" >
                        ${ i + 1 }
                      </a>
                    </li>
                {{/each}}
            </script>
        </div>
		
        <script id="personTemplate" type="text/html">
			    <tr>
					  <td data-bind="text: $index() + ($root.currentPage() * $root.pageSize()) + 1"></td>
					  <td>${ username }</td>
					  <td>${ name }</td>
            <td>${ email }</td>
            <td>${ group }</td>
            <td> ${ skpd }</td>
            <td data-bind="visible:false">${ apa }</td>
            <td>
              <button class="btn btn-primary" data-bind="enable:canSave, click: editMe">Edit</button>
            </td>
            <td>
              <button class="btn btn-primary" data-bind="enable:canSave, click: deleteMe">Delete</button>
            </td>
            <td>
              <div data-bind="visible: current == 'false'">
                <button class="btn btn-primary" data-bind="enable:canAktif,click: updateMe"><span data-bind="text: aktif"></span></button>
              </div>
              <div data-bind="visible: current == 'true'">
                <button class="btn btn-primary disabled"><span data-bind="text: aktif"></span></button>
              </div>
            </td>
			    </tr>
        </script>
				
		<!--<script src="<?php echo base_url()?>assets/script/jquery-1.6.2.min.js"></script>-->
        <!--<script src="<?php echo base_url()?>assets/script/jquery.tmpl.js"></script>-->
        <!--<script src="<?php echo base_url()?>assets/script/knockout-1.2.1.js"></script>-->
		<?php //print_r($users); ?>
        <script>
          function refresh (timeoutPeriod)
          {
            refresh = setTimeout(function(){window.location.reload(true);},timeoutPeriod);
          }
		
          (function ($)
            {
              var model = [
			  <?php 
			  		if($users)
					{
						$no = 1;
						foreach($users as $users)
						{
							
						
			  ?>
                {
	
				  name: "<?php echo isset($users['NAME'])?$users['NAME']:'' ?>", 
				  username: "<?php echo isset($users['USERNAME'])?$users['USERNAME']:'' ?>", 
				  email: "<?php echo isset($users['EMAIL'])?$users['EMAIL']:'' ?> ", 
				  skpd: "<?php echo isset($users['NAMA_SKPD'])?$users['NAMA_SKPD']:'' ?>", 
				  group: "<?php echo isset($users['GNAME'])?$users['GNAME']:'' ?>", 
				  id: "<?php echo isset($users['ID'])?$users['ID']:'' ?>", 
				  status: "<?php echo isset($users['STATUS'])?$users['STATUS']:'' ?>", 
				  apa: "<?php 
                if($users['STATUS'] == '1')
									echo 'enable';
									else echo 'disable';
               ?>", 
				  aktif: "<?php 
				  				if(isset($users['STATUS']))
								{
									if($users['STATUS'] == '1')
									echo 'Non-Aktifkan';
									else echo 'Aktifkan';
								}
						 ?>",
				  caption: "<?php 
				  				if(isset($users['STATUS']))
								{
									if($users['STATUS'] == '1')
									echo 'Non-aktifkan';
									else echo 'Aktifkan';
								}
						 ?>",
				  current: "<?php 
				  					if($users['ID'] == $this->session->userdata('id_user'))
									echo 'true';
									else echo 'false';
						 ?>",
          akseslevel    :ko.observable(<?php echo isset($akses) ? $akses : 0 ?>),
          canSave  : ko.computed(function()
          {
          //var akses = "<?php echo isset($akses) ? $akses : 0 ?>";
              return <?php echo isset($akses) ? $akses : 0 ?> >= 3;       
          }),
				  deleteMe: function ()
                  {
                    var agree=confirm("Apakah Anda yakin akan menghapus User '"+this.name+"' ?");
					
					if (agree)
					{
						//return true ;
						 var self = this;
						
						 data = {
				         id: this.id,
						 act:'delete',
						 status: this.status,
						 type: 'USERS',
				        };
						
						$.ajax({
				        type: "post",
				        dataType: "json",
						data: data,
				        url: root+modul+'/act',
				        success: function(res) {
							if (res.isSuccess) 
							{
								viewModel.people.remove(self);
								console.log(self)
								refresh('2400');
							}
							
					        $.pnotify({
					          title: res.isSuccess ? 'Sukses' : 'Gagal',
					          text: res.message,
					          type: res.isSuccess ? 'info' : 'error'
							  							  
					        });
				        },
						
				      	});
					}	
					else return false ;
					
                  },
				  updateMe: function ()
                  {
                    var agree=confirm("Apakah Anda yakin akan "+this.caption+" User '"+this.name+"' ?");
					
					if (agree)
					{
						//return true ;
						 var self = this;
						
						 data = {
				         id: this.id,
						 act:'update',
						 status: this.status,
						 type: 'USERS',
				        };
						
						$.ajax({
				        type: "post",
				        dataType: "json",
						data: data,
				        url: root+modul+'/act',
				        success: function(res) {
							if (res.isSuccess) 
							{
								//viewModel.people.remove(self);
								console.log(self)
								refresh('2400');
							}
							
					        $.pnotify({
					          title: res.isSuccess ? 'Sukses' : 'Gagal',
					          text: res.message,
					          type: res.isSuccess ? 'info' : 'error'
							  							  
					        });
				        },
						
				      	});
					}	
					else return false ;
					
                  },
           canAktif  : ko.computed(function()
          {
              return <?php echo isset($akses) ? $akses : 0 ?> >=3;       
          }),        
				  editMe: function ()
                  {
					var self = this;
				    id= this.id,
					window.location.href = "<?php echo base_url()?>group/user/"+id;
                  }
                },
				
              <?php
			  	
			  		$no++;
			  			}
					}
			  ?>  
              ],
              viewModel =
              {
			  	//var self = this;
                people: ko.observableArray(model),
                displayButton: ko.observable(true),
                displayForm: ko.observable(false),
                canAdd  : ko.computed(function()
                {
                var akses = "<?php echo isset($akses) ? $akses : 0 ?>";
                    return <?php echo isset($akses) ? $akses : 0 ?> >=3;       
                }),
				add: function ()
                {
                  window.location.href = "<?php echo base_url()?>group/user";
                },
                showForm: function ()
                {
                  this.displayForm(true).displayButton(false);
                },
                hideForm: function ()
                {
                  this.displayForm(false).displayButton(true);
                },
                currentPage: ko.observable(0),
                pageSize: ko.observable(5),
                navigate: function (data, event)
                {
					
				  var el = event.target;
				  
				  //if (el.id === "next")
                  if (el.id === "next")
                  {
                    if (this.currentPage() < this.totalPages() - 1)
                    {
                      this.currentPage(this.currentPage() + 1);
                    }
                  } else
                  {
                    if (this.currentPage() > 0)
                    {
                      this.currentPage(this.currentPage() - 1);
                    }
                  }
                },
                filterLetter: ko.observable(""),
                filterTerm: ko.observable(""),
                clearLetter: function ()
                {
                  this.filterLetter("");
                },
                clearTerm: function ()
                {
                  this.filterTerm("");
                  $("#term").val("");
                },
                setTerm: function ()
                {
                  this.filterTerm($("#term").val());
                }
              };

              //filtering / searching
              viewModel.filteredPeopleByTerm = ko.dependentObservable(function ()
                {
                  var term = this.filterTerm().toLowerCase();

                  if (!term)
                  {
                    return this.people();
                  }

                  return ko.utils.arrayFilter(this.people(), function (person)
                    {
                      var found = false;

                      for (var prop in person)
                      {
                        if (typeof (person[prop]) === "string")
                        {
                          if (person[prop].toLowerCase().search(term) !== -1)
                          {
                            found = true;
                            break;
                          }
                        }
                      }

                      return found;
                    });

                }, viewModel);

              viewModel.letters = ko.dependentObservable(function ()
                {
                  var result = [];

                  ko.utils.arrayForEach(this.filteredPeopleByTerm(), function (person)
                    {
                      result.push(person.name.charAt(0).toUpperCase());
                    });

                  return ko.utils.arrayGetDistinctValues(result.sort());
                }, viewModel);

              viewModel.filteredPeople = ko.dependentObservable(function ()
                {
                  var letter = this.filterLetter();
                  if (!letter)
                  {
                    return this.filteredPeopleByTerm();
                  }

                  return ko.utils.arrayFilter(this.filteredPeopleByTerm(), function (person)
                    {
                      return person.name.charAt(0).toUpperCase() === letter;
                    });
                }, viewModel);

              //paging
              viewModel.totalPages = ko.dependentObservable(function ()
                {
                  return Math.ceil(this.filteredPeople().length / this.pageSize());
                }, viewModel);

              viewModel.showCurrentPage = ko.dependentObservable(function ()
                {
                  if (this.currentPage() >= this.totalPages())
                  {
                    this.currentPage(this.totalPages() - 1);
                  }
                  var startIndex = this.pageSize() * this.currentPage();

                  return this.filteredPeople().slice(startIndex, startIndex + this.pageSize());
                }, viewModel);

              viewModel.numericPageSize = ko.dependentObservable(function ()
                {
                  if (typeof (this.pageSize()) !== "number")
                  {
                    this.pageSize(parseInt(this.pageSize()));
                  }
                }, viewModel);


              ko.applyBindings(viewModel);
            })(jQuery);
			
		</script>