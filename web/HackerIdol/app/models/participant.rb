require 'digest'
class Participant
    attr_reader :id, :name
    def initialize(name)
        @name = name
        @neo = Neography::Rest.new
        @id = nil
    end
    def exist
        return @neo.find_nodes_labeled("Participant", {"name" => @name}).length != 0
    end
    def valid(password)
        pwd = Digest::SHA256.hexdigest password
        node = @neo.find_nodes_labeled("Participant", {"password" => pwd}).first
        if node && node["data"]["name"] == @name
            @id = node['metadata']['id']
        end
    end
    def budget
        participant = @neo.find_nodes_labeled("Participant", {"name" => @name}).first
        hired = @neo.get_node_relationships(participant, "out", "HIRE")
        total = 100000
        hired.each do |hire|
        	total -= @neo.get_relationship_properties(hire, ["cost"])["cost"]
        end
        return total
    end
    def save(password)
    	pwd = Digest::SHA256.hexdigest password
        participant = @neo.create_node("name" => @name, "password" => pwd)
        @neo.set_label(participant, "Participant")
        agency = @neo.find_nodes_labeled("Agency", {:name => "Idol agency"})[0]
        @neo.create_relationship("WORK_WITH", participant, agency)
        @id = participant['metadata']['id']
    end
    def hired
    	participant = @neo.find_nodes_labeled("Participant", {"name" => @name}).first
    	hired = @neo.get_node_relationships(participant, "out", "HIRE")
    	stars = []
    	hired.each do |star|
    		stars.push(@neo.get_node(star['end']))
    	end
    	return stars
    end
    def hire(id)
    	participant = @neo.find_nodes_labeled("Participant", {"name" => @name}).first
    	begin
    		ids = id.split('-')
    		star = @neo.get_node(ids.last)
    		paths = @neo.get_paths(participant, star, {"type" => "WORK_WITH", "direction" => "out"}, depth=5, algorithm="allPaths")
    		path = nil
    		paths.each do |p|
    			nodes = ''
    			p["nodes"].each do |n|
    				intNode = @neo.get_node(n)
    				nodes += intNode["metadata"]["id"].to_s+'-'
    			end
    			if participant["metadata"]["id"].to_s+'-'+id+'-' == nodes
    				path = p
    				break
    			end
    		end
    		
    		if !path
    			return false, paths
    		end
    		total = star["data"]["cost"]
    		(1..path["length"]-1).each do |n|
    			node = @neo.get_node(path["nodes"][n])
    			total *= 1+node["data"]["marge"]
    		end
    		already = 0
    		hired.each do |s|
    			if s["data"]["name"] == star["data"]["name"]
    				already = 1
    				break
    			end
    		end
    		if already == 0
    			if self.budget-total.round(2) >= 0
    				hire = @neo.create_relationship("HIRE", participant, star)
    				@neo.set_relationship_properties(hire, {"cost" => total.round(2)})
    				return true, "Hired"
    			else
    				return false, "Not enough money"
    			end
    		else
    			return false, "Already hired"
    		end
    	rescue
    		return false, "Error"
    	end
    end

    def search(skill)
        participant = @neo.find_nodes_labeled("Participant", {"name" => @name}).first
        fullpaths = @neo.traverse(
            participant,
            "fullpaths",{
                "order" => "depth first",
                "uniqueness" => "relationship global",
                "relationships" => [{
                    "type"=> "WORK_WITH",
                    "direction" => "out"
                }],
                "return filter" => {
                	"language" => "javascript",
                    "body" => "var n = position.endNode(); n.hasProperty('skill') && n.getProperty('skill').equals('#{skill}')"
                },
                "depth" => 4
            })

       	return fullpaths
    end

    def fire
    	participant = @neo.find_nodes_labeled("Participant", {"name" => @name}).first
    	hired = @neo.get_node_relationships(participant, "out", "HIRE")
    	hired.each do |rel|
    		@neo.delete_relationship(rel)
    	end
    end

    def validate
    	participant = @neo.find_nodes_labeled("Participant", {"name" => @name}).first
    	stars = self.hired
    	actors = 0
    	director = 0
    	cameraman = 0
    	scenarist = 0
    	notoriety = 0
    	error = ''
    	stars.each do |star|
    		if star["data"]["skill"] == "Actor"
    			actors+=1
    		elsif star["data"]["skill"] == "Cameraman"
    			cameraman+=1
    		elsif star["data"]["skill"] == "Scenarist"
    			scenarist+=1
    		elsif star["data"]["skill"] == "Director"
    			director+=1
    		end
    		notoriety+=star["data"]["notoriety"]
    	end
    	if director != 1
    		error += 'You must have 1 director ; '
    	end
    	if cameraman != 1
    		error += 'You must have 1 cameraman ; '
    	end
    	if scenarist != 1
    		error += 'You must have 1 scenarist ; '
    	end
    	if actors != 2
    		error += 'You must have 2 actors ; '
    	end
    	if error == ''
    		if notoriety < 47
    			error = 'Thanks for your casting, but we got a winner with a better one !'
    			return false, error
    		else
    			return true, ''
    		end
    	else	    		
    		return false, error
    	end
    end
end