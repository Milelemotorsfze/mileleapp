                                @if($addonsdata->image)
                               
                                    <img id="myImg_{{$addonsdata->id}}" class="image-click-class" src="{{ asset('addon_image/' . $addonsdata->image) }}"
                                         alt="Addon Image" style="max-height:159px; max-width:232px;">
                               @else
                               <img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
                               style="max-height:159px; max-width:232px;" alt="Addon Image"  />
                                @endif
                                <script type="text/javascript">
                                    $(document).ready(function ()
                                    {
                                        // show image in large view
                                        $('.image-click-class').click(function (e)
                                        {
                                            var id =  $(this).attr('id');
                                            var src = $(this).attr('src');
                                            var modal = document.getElementById("myModal");
                                            var img = document.getElementById(id);
                                            var modalImg = document.getElementById("img01");
                                            var captionText = document.getElementById("caption");
                                            modal.style.display = "block";
                                            modalImg.src = src;
                                            captionText.innerHTML = this.alt;
                                        })
                                        $('.closeImage').click(function (e)
                                        {
                                            var modal = document.getElementById("myModal");
                                            modal.style.display = "none";
                                        })
                                    });
                                </script>