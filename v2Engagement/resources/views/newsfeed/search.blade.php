 
<div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
            <table cellspacing="0" cellpadding="0" padding-right:10px;>
                    @foreach($newsLists as $newslist)        
                        <tr id="{{$newslist->id}}">
                        <td class="nf_seg_name" style="width:34%;">

                            <a href="#">
                            @if(!empty($newslist->image_url))
                              <img src="{{ $newslist->image_url }}" alt="#" style="width: 100px;"> 
                            @else
                            <img src="{{ asset('/assets/images/nf_list_img7.png') }}" alt="#" style="width: 100px;"> 
                            @endif
                              {{$newslist->name}}
                            </a>
                            <div class="nf_seg_name_detail  hide ">
                              <table>
                                <tr>
                                  <td style="width:60%;">Target Users</td>
                                  <td>--</td>
                                </tr>
                                <tr>
                                  <td style="width:60%;">Segment</td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td style="width:60%;">Clicks(Unique)</td>
                                  <td>0(0)</td>
                                </tr>
                                <tr>
                                  <td style="width:60%;">Impressions(Unique)</td>
                                  <td>0(0)</td>
                                </tr>
                                <tr>
                                  <td style="width:60%;">Clickthrough Rate(Unique)</td>
                                  <td>0.00% (0.00%)</td>
                                </tr>
                              </table>
                            </div>
                          </td>
                          <td style="width:15%;">{{ $newslist->template }}</td>
                          <td style="width:10%;">0 (0)</td>
                          <td class="list_time_date" style="width:10%;">
                          {{ Carbon\Carbon::parse($newslist->start_time)->format('d/m/Y') }}
                           <b>
                          {{ Carbon\Carbon::parse($newslist->start_time)->format('h:i:s A') }}
                            </b>  </td>
                          <td class="list_time_date" style="width:10%;">
                          {{ Carbon\Carbon::parse($newslist->start_time)->format('d/m/Y') }}
                          <b>
                          {{ Carbon\Carbon::parse($newslist->start_time)->format('h:i:s A') }}
                          </b> 
                          </td>
                          <td style="width:15%;">@if($newslist->is_active == 1)
                              Active
                              @else
                              Draft
                              @endif
                          </td>
                          <td style="width:6%;">
                            <div class="lst_tbl_drop_outer">
                                <span class=""> <img src="{{asset('/assets/images/sett_icon.png')}}"  > </span>
                                <ul>
                                  <li id="{{$newslist->id}}" data-action="edit"><a href="{{ route('editNewsfeed',$newslist->id) }}"> <img src="{{asset('/assets/images/edit_icon.png')}}" alt="">Edit </a>  </li>
                                  <li> <a href="#" onclick="newsDelete({{$newslist->id}})"> <img src="{{asset('/assets/images/view_icon.png')}}" alt="">Delete </a>  </li>
                                </ul>
                            </div>
                          </td>
                        </tr>
                        @endforeach
                    </table>
</div>
 <script src="{{asset('/assets/js/my_script.js')}}"></script>