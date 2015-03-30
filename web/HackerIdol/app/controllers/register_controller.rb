class RegisterController < ApplicationController
  	def index
  	end
  	def create
		participant = Participant.new(params[:participant][:name])
		if participant.exist
			session[:error] = 'Already exist'
		else
			participant.save(params[:participant][:password])
			session[:participant_id] = participant.id
			session[:participant_name] = params[:participant][:name]
		end
		redirect_to '/'
  	end
end
