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
            
		  <form class="form-search" id="searchForm"   >
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
		<button class="btn btn-success" data-bind="enable:canAdd ,click: add" >Tambah</button> <br /><br />
		<table class="table table-striped"> 
				  <tr>
			        <th>#</th>
			        <th>Nama</th>
			        <th>Deskripsi</th>
					<th>Edit</th>
					<th>Delete</th>
					<!--<th>Status</th>-->
			      </tr>
		<tbody data-bind="template: { name: 'personTemplate', foreach: showCurrentPage }"></tbody>
		
		</table>
		
		<label id="pageSize">Show <input class="input-mini" type="text" data-bind="value: pageSize" /> of <?php echo $count; ?></label>
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
        </div>
		
        <script id="personTemplate" type="text/html">
			    <tr>
					<td data-bind="visible: $root.currentPage() == '0',text: $index() + 1"></td>
					<td data-bind="visible: $root.currentPage() > '0',text: $index() + ($root.currentPage() * $root.pageSize()) + 1"></td>
					<td>${ name }</td>
                	<td>${ desc }</td>
					<td>
                    	<button class="btn btn-primary" data-bind="enable:canSave, click:editMe" id="edit">Edit</button>
                	</td>
                	<td>
                    	<button class="btn btn-primary" data-bind="enable:canSave, click: deleteMe"  id="delete">Delete</button>
                	</td>
					
			    </tr>
        </script>
 
                        
				
        <script>
          function refresh (timeoutPeriod)
          {
            refresh = setTimeout(function(){window.location.reload(true);},timeoutPeriod);
          }
		
          (function ($)
            {
              var model = [
			  <?php 
			  		if($group)
					{
						foreach($group as $group)
						{
							
						
			  ?>
                {
                  name: "<?php echo isset($group['NAME'])?$group['NAME']:'' ?>", 
				  desc: "<?php echo isset($group['DESCRIPTION'])?$group['DESCRIPTION']:'' ?>", 
				  id: "<?php echo isset($group['ID'])?$group['ID']:'' ?>", 
				  status: "<?php echo isset($group['STATUS'])?$group['STATUS']:'' ?>",
				  aktif: "<?php 
				  				if(isset($group['STATUS']))
								{
									if($group['STATUS'] == '1')
									echo 'Aktif';
									else echo 'Non-Aktif';
								}
						 ?>",
				  caption: "<?php 
				  				if(isset($group['STATUS']))
								{
									if($group['STATUS'] == '1')
									echo 'Non-aktifkan';
									else echo 'Aktifkan';
								}
						 ?>", 
          canSave  : ko.computed(function()
          {
              return <?php echo isset($akses) ? $akses : 0 ?> >=3;       
          }),
				  deleteMe: function ()
                  {
                    var agree=confirm("Apakah Anda yakin akan menghapus Group '"+this.name+"' ?");
					
					if (agree)
					{
						//return true ;
						 var self = this;
						
						 data = {
				         id: this.id,
						 act:'delete',
						 status: this.status,
						 type: 'GROUPS',
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
                    var agree=confirm("Apakah Anda yakin akan "+this.caption+" Group '"+this.name+"' ?");
					
					if (agree)
					{
						//return true ;
						 var self = this;
						
						 data = {
				         id: this.id,
						 act:'update',
						 status: this.status,
						 type: 'GROUPS',
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
				  editMe: function ()
                  {
					var self = this;
				    id= this.id,
					window.location.href = "<?php echo base_url()?>group/groups/"+id;
                  }
                },
				
              <?php
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
                add: function ()
                {
                  window.location.href = "<?php echo base_url()?>group/groups";
				  //alert(viewModel.people.id);
                },
                 canAdd  : ko.computed(function()
                {          
                    return <?php echo isset($akses) ? $akses : 0 ?> >=3;
                }),
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
                navigate: function (e, event)
                {
                  var el = event.target;

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