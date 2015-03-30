class SearchController < ApplicationController
  def index
  	participant = Participant.new(session[:participant_name])
  	@paths = participant.search(params[:search][:skill])
  end
end
