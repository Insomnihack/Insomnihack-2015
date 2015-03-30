class HomeController < ApplicationController
  def index
  	if session[:participant_id]
  		participant = Participant.new(session[:participant_name])
  		@stars = participant.hired
  		@budget = participant.budget.round(2)
  	end
  end
  def signIn
  	participant = Participant.new(params[:participant][:name])
  	a = participant.valid(params[:participant][:password])
  	if participant.id
  		session[:participant_id] = participant.id
  		session[:participant_name] = params[:participant][:name]
  	else
  		session[:error] = "Invalid user"
  	end
  	redirect_to '/'
  end
  def logout
  	reset_session
  	redirect_to '/'
  end
end
