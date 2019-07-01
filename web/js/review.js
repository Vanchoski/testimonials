window.Review = {
    filters : {
        ratings:null,
        q:null,
        page:1,
        perPage:5
    },
    myPagination: null,

    init: function () {

        $("#leaveReview").on("hidden.bs.modal", function () {
            $('#error-msg').hide();
        });

        var that = this;
        this.myPagination =  new Pagination({
            pageClickCallback: function(page){
                that.filters.page = page;
                that.reloadReviews();
            },
            container: $("#pagination-container")
        });

        this.reloadReviews();
    },
    reloadReviews: function () {
        let that = this;
        $('#error-msg').hide();
        $.ajax({
            dataType:'json',
            url: 'testimonials?'+$.param(that.filters),
            type: "GET",
            success: function (data) {
                $( ".review-container" ).empty();

                $.each(data.reviews, function (index, element) {
                    that.build_html(element);
                });

                $(".rateit").rateit();

                that.myPagination.make(data.total, data.per_page, data.current_page);
            }
        });
    },
    addReview:function (){
        var that = this;
        var formData = {
            full_name : $('#name').val(),
            email : $('#email').val(),
            rating : $('#star-rating').rateit('value'),
            message : $('#message').val()
        };
        $.ajax({
            url: 'testimonials/add',
            method: 'POST',
            data:formData,
            success: function (data) {
                $('#leaveReview').modal('hide');
                that.reloadReviews();
                $("#leave_review").trigger("reset");
                $('#error-msg').hide();
            },
            error: function (xhr) {
                console.log('adas');
                $('#error-msg').show();
            }
        })
    },
    build_html: function (review){
        var initial = this.getInitials(review);

        $('.review-container').append(
            "<div class='review-wraper'>"+
            "<div class='row'>" +
            "<div class='col-xs-3'>" +
            "<div class='profile'>"+
            "<div class='avatar-circle'><p>"+initial+"</p></div>" +
            "<div class='name-text'><p>"+review.fullName+"</p></div>" +
            "</div>" +
            "</div>" +
            "<div class='col-xs-9'>" +
            "<div class='rateit' data-rateit-value="+review.rating+" data-rateit-ispreset='true' data-rateit-readonly='true'></div>"+
            "<p>"+review.message+"</p>" +
            "</div>" +
            "</div>"+
            "</div>"
        );
    },
    getInitials : function (string) {
        let names = string.fullName.split(' ');
        let  initials = names[0].substring(0, 1).toUpperCase();
        return initials;
    }
};

$(document).ready(function() {
    window.Review.init();
    $('.raiting-check:checkbox').change(function()
    {
        window.Review.filters.ratings = [];
        var checked = $('.raiting-check:checkbox:checked');
        $.each(checked, function (index, element) {
            window.Review.filters.ratings.push($(element).data('star'));
        });
        window.Review.reloadReviews();
    });

    $('#submit_review').on('click', function () {
        window.Review.addReview();
    });

    $('#search_button').on('click', function () {
        window.Review.filters.q = $('#filter-reviews').val();
        window.Review.reloadReviews();
    });

    $('#filter-reviews').keyup(function (e){
        if(e.keyCode === 13){
            window.Review.filters.q = $('#filter-reviews').val();
            window.Review.reloadReviews();
        }
    });
});