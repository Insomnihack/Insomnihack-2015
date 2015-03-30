class SubmitController < ApplicationController
  def index
  	participant = Participant.new(session[:participant_name])
  	result = participant.validate
  	if result[0]
  		@flag = 'this is a winner flag'
  	else
  		participant.fire
  		session[:error] = result[1]
  		redirect_to '/'
  	end
  end
end
