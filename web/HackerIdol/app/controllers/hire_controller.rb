class HireController < ApplicationController
  def index
  	participant = Participant.new(session[:participant_name])
  	a = participant.hire(params[:id])
  	if a[0]
  		redirect_to '/'
  	else
  		session[:error] = a[1]
  		redirect_to request.referer
  	end
  end
end
