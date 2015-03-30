class ParticipantsController < ApplicationController
	def new
	end
	def create
		Participant.new(params.require(:participant).permit(:name, :password))
		redirect_to @participant
	end
end
